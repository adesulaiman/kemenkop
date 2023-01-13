<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if (isset($_SESSION['userid'])) {
  if (isset($_GET['f'])) {
    $f = $_GET['f'];

    $qForm = $adeQ->select($adeQ->prepare(
      "select * from core_forms where idform=%d",
      $f
    ));

    $qField = $adeQ->select($adeQ->prepare(
      "select * from core_fields where id_form=%d and active is true order by id",
      $f
    ));

    $qFieldSelect = $adeQ->select($adeQ->prepare(
      "select * from core_fields where id_form=%d and active is true and type_input in ('select', 'checkbox') order by id",
      $f
    ));

    foreach ($qForm as $valForm) {
      $formName = $valForm['formname'];
      $formDesc = $valForm['description'];
      $formCode = $valForm['formcode'];
      $formview = $valForm['formview'];
    }

?>
    <section class="content-header">
      <h1>
        ODS System V.1.2
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo $formDesc ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="<?php echo $formName ?>" class="table table-bordered table-striped nowrap">
                <thead>
                  <tr>
                    <?php
                    foreach ($qField as $valField) {
                      echo "<th>" . ucfirst(str_replace("_", " ", $valField['name_field'])) . "</th>";
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>

    </section>


    <!-- Modal Add New Data -->
    <div class="modal fade" id="Modal<?php echo $formName ?>" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="ModalText<?php echo $formName ?>"></h4>
          </div>
          <div class="modal-body">
            <div class="box-body">
              <form class="formModal<?php echo $formName ?>" action='javascript:void(0);' method='post'>

              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default ladda-button" data-color="grey" style="padding:5px 20px;" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary formSubmit ladda-button" style="padding:5px" data-color="green" data-style="contract">Submit</button>
            <button type="button" class="btn btn-primary actFilter ladda-button" style="padding:5px" data-color="green" data-style="contract">Filter</button>
          </div>
        </div>
      </div>
    </div>


    <script>
      var loading = Ladda.create(document.querySelector('.formSubmit'));

      var table = $('#<?php echo $formName ?>').DataTable({
        "dom": 'Bltip',
        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": "./lib/base/load_data.php?t=<?php echo $formview ?>&f=<?php echo $f ?>",
          "data": function(data) {
            var dtQuery = $(".queryFilter").val();
            data.query = dtQuery;
          }
        },
        "searching": false,
        "scrollX": true,
        scrollCollapse: true,
        "lengthMenu": [
          [5],
          [5]
        ],
        "columns": [
          <?php
          foreach ($qField as $valField) {
            echo "{ data: '$valField[name_field]', width:'150px' },";
          }
          ?>
        ],
        buttons: [
          {
            text: '<i class="fa fa-plus-circle"></i> Add',
            action: function(e, dt, node, config) {
              loadForm(null, "add");

            }
          },
          {
            text: '<i class="fa fa-pencil-square-o"></i> Edit',
            action: function(e, dt, node, config) {
              var rowData = dt.rows(".selected").data()[0];

              if (rowData == null) {

                popup('error', 'Mohon pilih data terlebih dahulu', '');
              } else {
                loadForm(rowData.id, "edit");
              }


            }
          },
          {
            text: '<i class="fa fa-trash-o"></i> Delete',
            action: function(e, dt, node, config) {
              var rowData = dt.rows(".selected").data()[0];

              if (rowData == null) {
                popup('error', 'Mohon pilih data terlebih dahulu', '');
              } else {
                loadFormTw(rowData.id, "delete");
              }
            }
          },
          {
            text: '<i class="fa fa-search"></i> Search',
            action: function(e, dt, node, config) {
              loadFormTw(null, "search");
            }
          },
          {
            text: '<i class="fa fa-refresh"></i> Refresh',
            action: function(e, dt, node, config) {
              $('.queryFilter').val('');
              table.draw();
            }
          }
        ],
        select: {
          style: 'single'
        },
        "columnDefs": [{
            "targets": [0],
            "visible": false
          },
          {
            "targets": [2],
            "visible": false
          }
        ]
      });




      $('.formSubmit').on('click', function() {
        loading.start();

        var form = $('.formModal<?php echo $formName ?>').serialize();
        console.log(form);
        $.ajax({
          method: "POST",
          url: "./lib/base/save_user.php",
          data: form + '&f=<?php echo $f ?>',
          dataType: 'json',
          success: function(msg) {
            console.log(msg);
            $.each(msg.validate, function(index, value) {
              if (value.err == 'validate') {
                $('.grp' + value.field).removeClass("has-error").addClass("has-error");
                $('.err' + value.field).html(value.msg);
              } else {
                $('.grp' + value.field).removeClass("has-error");
                $('.err' + value.field).html(null);
              }
            })

            if (msg.status) {
              table.ajax.reload();
              popup('success', 'Success', '');
              $('#Modal<?php echo $formName ?>').modal('toggle');
            }

            loading.stop()
          },
          error: function(err) {
            popup('error', 'Error System', '');
            console.log(err);
            loading.stop()
          }
        });
      })



      $('.actFilter').on('click', function() {
        var filter = new Array();
        var logic = new Array();
        var valFilter = new Array();
        var query = new Array();

        $('.filter').each(function() {
          filter.push($(this).val());
        });
        $('.logic').each(function() {
          logic.push($(this).val());
        });
        $('.valueFilter').each(function() {
          valFilter.push($(this).val());
        });

        for (i = 0; i < filter.length; i++) {
          if (logic[i] == 'like') {
            query.push('UPPER(' + filter[i] + ') ' + logic[i] + " '%" + valFilter[i].toUpperCase() + "%'");
          } else {
            query.push(filter[i] + ' ' + logic[i] + " '" + valFilter[i] + "'");
          }
        }
        $('.queryFilter').val(query.join(' and '));

        table.draw();
        $('#Modal<?php echo $formName ?>').modal('toggle');

      })


      function loadForm(id, type) {
        if (id == null) {
          var val = '';
        } else {
          var val = "&v=" + id;
        }

        $.ajax({
          method: "POST",
          url: "./lib/base/form_modal_act.php?f=<?php echo $f ?>&type=" + type + val,
          success: function(msg) {
            // console.log(JSON.parse(msg));
            var fData = JSON.parse(msg)
            $('#ModalText<?php echo $formName ?>').text(fData.type);
            $('.formModal<?php echo $formName ?>').html(fData.data);

            $('.datepicker').datepicker({
              format: '<?php echo $dateJS ?>',
              autoclose: true
            });

            <?php
            foreach ($qFieldSelect as $select) {
              if ($select['case_cade'] != null) {
                $qCaseCade = $adeQ->select($adeQ->prepare("select * from core_fields where id_form=%d and id=%d", $f, $select['case_cade']));
                foreach ($qCaseCade as $caseCade) {
                  echo "
                        var id = $('.$caseCade[name_field]').val();
                        if(id.length == 0)
                        {
                          $('.$select[name_field]').prop('disabled', true);
                          $('.$caseCade[name_field]').on('change', function(){
                              $('.$select[name_field]').prop('disabled', false);
                              var id = $(this).val();
                              $('.$select[name_field]').val(null).trigger('change');
                              $('.$select[name_field]').select2({
                                ajax: {
                                  url: '$select[link_type_input]&filter=' + id,
                                  dataType: 'json',
                                  data: function (params) {
                                    return {search: params.term};
                                  }
                                }
                            });
                         });
                        }else{
                          $('.$select[name_field]').prop('disabled', false);
                          $('.$select[name_field]').select2({
                                ajax: {
                                  url: '$select[link_type_input]&filter=' + id,
                                  dataType: 'json',
                                  data: function (params) {
                                    return {search: params.term};
                                  }
                                }
                            });
                            $('.$caseCade[name_field]').on('change', function(){
                              $('.$select[name_field]').prop('disabled', false);
                              var id = $(this).val();
                              $('.$select[name_field]').val(null).trigger('change');
                              $('.$select[name_field]').select2({
                                ajax: {
                                  url: '$select[link_type_input]&filter=' + id,
                                  dataType: 'json',
                                  data: function (params) {
                                    return {search: params.term};
                                  }
                                }
                            });
                         });
                        }
                       
                      ";
                }
              } else {
                echo "
                      $('.$select[name_field]').select2({
                        ajax: {
                          url: '$select[link_type_input]',
                          dataType: 'json',
                          data: function (params) {
                            return {search: params.term};
                          }
                        }
                    });
                    ";
              }
            }
            ?>

            $('.actFilter').css('display', 'none');
            $('.formSubmit').css('display', 'inline');

            $('#Modal<?php echo $formName ?>').modal('show');
          }
        });
      }




      function loadFormTw(id, type) {
        if (id == null) {
          var val = '';
        } else {
          var val = "&v=" + id;
        }

        $.ajax({
          method: "POST",
          url: "./lib/base/form_modal_act.php?f=<?php echo $f ?>&type=" + type + val,
          success: function(msg) {
            // console.log(JSON.parse(msg));
            var fData = JSON.parse(msg)
            $('#ModalText<?php echo $formName ?>').text(fData.type);
            $('.formModal<?php echo $formName ?>').html(fData.data);
            dynamicSearch(fData.data);

            $('.datepicker').datepicker({
              format: '<?php echo $dateJS ?>',
              autoclose: true
            });

            if (type == 'search') {
              $('.formSubmit').css('display', 'none');
              $('.actFilter').css('display', 'inline');
            } else {
              $('.actFilter').css('display', 'none');
              $('.formSubmit').css('display', 'inline');
            }


            $('#Modal<?php echo $formName ?>').modal('show');
          }
        });
      }




      function dynamicSearch(form) {
        var next = 1;
        var i = 1;
        var formFilter = $(".formFilter").html();
        $(".add-more").click(function(e) {
          e.preventDefault();
          next = next + 1;
          var newIn = formFilter.replace("formRow", "formRow" + next);
          var newInput = $(newIn);
          var removeBtn = "<div class='col-md-2'><button data-id='" + next + "' class='btn btn-danger remove-me' >-</button></div>";
          $(".formFilter").after(newInput);
          $('.formRow' + next).append(removeBtn);
          $('.remove-me').click(function(e) {
            e.preventDefault();
            var fieldNum = $(this).data('id');
            var fieldID = ".formRow" + fieldNum;
            $(fieldID).remove();
          });
        });
      }
    </script>


<?php
  } //close $f
} // close session
?>
<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

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
  $formView = $valForm['formview'];
  $formDesc = $valForm['description'];
  $formCode = $valForm['formcode'];
}

//SHOW SCHEMA VIEW
$qSchemaView = $adeQ->select($adeQ->prepare(
  "select * from information_schema.columns where table_name=%s order by ordinal_position",
  $formView
));

$qFilter = $adeQ->select($adeQ->prepare("select * from core_filter
                                            where idform=%s
                                            and gropname='header'
                                            order by id", $f));
?>


<section class="content-header">
  <h1>
    <?php echo $formDesc ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Menus</a></li>
    <li class="active"><?php echo $formDesc ?></li>
  </ol>
</section>


<?php if (count($qFilter) > 0) { ?>
  <!-- FILTER DATA -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <!-- /.box-header -->
          <div class="box-header with-border">
            <h3 class="box-title">Filter Data</h3>
          </div>
          <div class="box-body">
            <form class="filterForm">
              <div class="row">
                <?php


                $type1 = array('text', 'email', 'password', 'number');
                $type2 = array('select');
                $type3 = array('checkbox');
                $type4 = array('date');

                foreach ($qFilter as $valField) {
                  if (in_array($valField['type_input'], $type1)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <input type='$valField[type_input]' name='$valField[name_field]' class='form-control fil$valField[name_field]' placeholder='Enter $valField[name_input]'>
                          </div>
                        ";
                  } elseif (in_array($valField['type_input'], $type2)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
                                <option value=''>Please Select $valField[name_input]</option>
                              </select>
                          </div>
                        ";


                    if ($valField['case_cade'] != null) {
                      $qCaseCadeFilter = $adeQ->select("select * from core_filter where id=$valField[case_cade]");
                      echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil" . $qCaseCadeFilter[0]['name_field'] . "').on('change', function(){
                                $('.fil$valField[name_field]').prop('disabled', false);
                                var id = $(this).val();
                                $('.fil$valField[name_field]').val(null).trigger('change');
                                $('.fil$valField[name_field]').select2({
                                  ajax: {
                                    url: '$valField[link_type_input]&filter=' + id,
                                    dataType: 'json',
                                    data: function (params) {
                                      return {search: params.term};
                                    }
                                  }
                              });
                           });    
                          </script>
                          ";
                    } else {
                      echo "
                          <script>
                            $('.fil$valField[name_field]').select2({
                              ajax: {
                                url: '$valField[link_type_input]',
                                dataType: 'json',
                                data: function (params) {
                                  return {search: params.term};
                                }
                              }
                          });
                          </script>
                          ";
                    }
                  } elseif (in_array($valField['type_input'], $type3)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input] </label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
                                
                              </select>
                          </div>
                        ";

                    $qCaseCadeFilter = $adeQ->select("select * from core_filter where case_cade=$valField[case_cade]");
                    if ($qCaseCadeFilter) {
                      echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil" . $qCaseCadeFilter[0]['name_field'] . "').on('change', function(){
                                $('.fil$valField[name_field]').prop('disabled', false);
                                var id = $(this).val();
                                $('.fil$valField[name_field]').val(null).trigger('change');
                                $('.fil$valField[name_field]').select2({
                                  ajax: {
                                    url: '$valField[link_type_input]&filter=' + id,
                                    dataType: 'json',
                                    data: function (params) {
                                      return {search: params.term};
                                    }
                                  }
                              });
                           });    
                          </script>
                          ";
                    } else {
                      echo "
                          <script>
                            $('.fil$valField[name_field]').select2({
                              ajax: {
                                url: '$valField[link_type_input]',
                                dataType: 'json',
                                data: function (params) {
                                  return {search: params.term};
                                }
                              }
                          });
                          </script>
                          ";
                    }
                  } elseif (in_array($valField['type_input'], $type4)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <input type='text' class='datepicker form-control fil$valField[name_field]' name='$valField[name_field]' placeholder='Enter $valField[name_input]'>
                          </div>
                        ";
                  }
                }

                ?>

              </div>

              <div class="row" style="margin: 20px 0">
                <div class="col-md-12">
                  <label>Advance Filter</label>
                  <label class="switch">
                    <input type="checkbox" class="filterAdvCheck">
                    <span class="slider round"></span>
                  </label>
                </div>

              </div>

              <div class="row advanceFilter" style="margin-top: 10px;display: none">
                <?php
                $qFilter = $adeQ->select($adeQ->prepare("select * from core_filter
                                            where idform=%s
                                            and gropname='advance'
                                            order by id", $f));

                $type1 = array('text', 'email', 'password', 'number');
                $type2 = array('select');
                $type3 = array('checkbox');
                $type4 = array('date');

                foreach ($qFilter as $valField) {
                  if (in_array($valField['type_input'], $type1)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <input type='$valField[type_input]' name='$valField[name_field]' class='form-control fil$valField[name_field]' placeholder='Enter $valField[name_input]'>
                          </div>
                        ";
                  } elseif (in_array($valField['type_input'], $type2)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
                                <option value=''>Please Select $valField[name_input]</option>
                              </select>
                          </div>
                        ";


                    if ($valField['case_cade'] != null) {
                      $qCaseCadeFilter = $adeQ->select("select * from core_filter where id=$valField[case_cade]");
                      echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil" . $qCaseCadeFilter[0]['name_field'] . "').on('change', function(){
                                $('.fil$valField[name_field]').prop('disabled', false);
                                var id = $(this).val();
                                $('.fil$valField[name_field]').val(null).trigger('change');
                                $('.fil$valField[name_field]').select2({
                                  ajax: {
                                    url: '$valField[link_type_input]&filter=' + id,
                                    dataType: 'json',
                                    data: function (params) {
                                      return {search: params.term};
                                    }
                                  }
                              });
                           });    
                          </script>
                          ";
                    } else {
                      echo "
                          <script>
                            $('.fil$valField[name_field]').select2({
                              ajax: {
                                url: '$valField[link_type_input]',
                                dataType: 'json',
                                data: function (params) {
                                  return {search: params.term};
                                }
                              }
                          });
                          </script>
                          ";
                    }
                  } elseif (in_array($valField['type_input'], $type3)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input] </label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
                                
                              </select>
                          </div>
                        ";

                    $qCaseCadeFilter = $adeQ->select("select * from core_filter where case_cade=$valField[case_cade]");
                    if ($qCaseCadeFilter) {
                      echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil" . $qCaseCadeFilter[0]['name_field'] . "').on('change', function(){
                                $('.fil$valField[name_field]').prop('disabled', false);
                                var id = $(this).val();
                                $('.fil$valField[name_field]').val(null).trigger('change');
                                $('.fil$valField[name_field]').select2({
                                  ajax: {
                                    url: '$valField[link_type_input]&filter=' + id,
                                    dataType: 'json',
                                    data: function (params) {
                                      return {search: params.term};
                                    }
                                  }
                              });
                           });    
                          </script>
                          ";
                    } else {
                      echo "
                          <script>
                            $('.fil$valField[name_field]').select2({
                              ajax: {
                                url: '$valField[link_type_input]',
                                dataType: 'json',
                                data: function (params) {
                                  return {search: params.term};
                                }
                              }
                          });
                          </script>
                          ";
                    }
                  } elseif (in_array($valField['type_input'], $type4)) {
                    echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <input type='text' class='datepicker form-control fil$valField[name_field]' name='$valField[name_field]' placeholder='Enter $valField[name_input]'>
                          </div>
                        ";
                  }
                }

                ?>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <input type="hidden" class="queryFilter" />
                  <button class="btn btn-success actFilter2" type="button">Filter</button>
                  <button class="btn btn-danger resetFilter" type="button">Reset</button>
                </div>
              </div>

            </form>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>
<?php } ?>


<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <table id="<?php echo $formName ?>" class="table table-bordered table-striped nowrap">
            <thead>
              <tr>
                <?php
                foreach ($qSchemaView as $valField) {
                  if (substr($valField['column_name'], 0, 3) != 'id_') {
                    echo "<th>" . ucfirst(str_replace("_", " ", $valField['column_name'])) . "</th>";
                  }
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
          <form class="formModal<?php echo $formName ?>" action='#' method='post'>

          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary formSubmit">Submit</button>
        <button type="button" class="btn btn-primary formUpload">Upload</button>
        <button type="button" class="btn btn-primary actFilter">Filter</button>
      </div>
    </div>
  </div>
</div>


<!-- dialog multi value -->

<div class="dialog-multivalue box-body">
</div>


<script>
  $('.datepicker').datepicker({
    format: '<?php echo $dateJS ?>',
    autoclose: true
  });


  $('.filterAdvCheck').click(function() {
    if ($(this).prop("checked") == true) {
      $('.advanceFilter').css('display', 'block');
    } else {
      $('.advanceFilter').css('display', 'none');
    }
  });

  dialog = $(".dialog-multivalue").dialog({
    autoOpen: false,
    height: 400,
    width: 350,
    title: "Add Tlp Mobile",
    appendTo: "#Modal<?php echo $formName ?>",
    modal: true,
    buttons: {
      "Add": function() {
        var tlp = new Array();
        $('.tlpMulti').each(function() {
          tlp.push($(this).val());
        });
        $(".tlp_mobile").val(tlp.join('|'));
        dialog.dialog("close");
      },
      Cancel: function() {
        dialog.dialog("close");
      }
    },
    close: function() {

    }
  });



  var table = $("#<?php echo $formName ?>").DataTable({
    "dom": 'Bltip',
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "./lib/ods/load_data_ods_pemusaha.php?t=<?php echo $formView ?>&f=<?php echo $f ?>",
      "type": "POST",
      "data": function(data) {
        var dtQuery = $(".queryFilter").val();
        data.query = dtQuery;
      }
    },
    "searching": false,
    "scrollX": true,
    scrollCollapse: true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ],
    "columns": [
      <?php
      foreach ($qSchemaView as $valField) {
        if (substr($valField['column_name'], 0, 3) != 'id_') {
          echo "{ data: '$valField[column_name]' },";
        }
      }
      ?>
    ],
    buttons: [{
        text: '<i class="fa fa-check"></i> Approve',
        action: function(e, dt, node, config) {
          var rowData = dt.rows(".selected").data()[0];

          if (rowData == null) {
            alert('Mohon pilih data terlebih dahulu');
          } else {

            var rows = table.column(0).checkboxes.selected();
            // Iterate over all selected checkboxes
            rows_selected = [];
            $.each(rows, function(index, rowId) {
              // Create a hidden element 
              rows_selected.push("'" + rowId + "'");
            });

            $.ajax({
              method: "POST",
              url: "./lib/ods/save_data_advance.php",
              data: 'id='+rows_selected.join(',')+'&f=<?php echo $f ?>&formType=approve_pem_usaha',
              dataType: 'json',
              success: function(msg) {
                table.ajax.reload();
                popup(msg.status, msg.msg, '');
              },
              error: function(err) {
                console.log(err);
                if (err.responseText.indexOf("duplicate key") != -1) {
                  popup('error', 'Error Insert, Data Duplicate !!', '');
                } else {
                  popup('error', err.responseText, '');
                }
              }
            });
          }


        }
      },
      {
        text: '<i class="fa fa-close"></i> Reject',
        action: function(e, dt, node, config) {
          var rowData = dt.rows(".selected").data()[0];

          if (rowData == null) {
            alert('Mohon pilih data terlebih dahulu');
          } else {
            var rows = table.column(0).checkboxes.selected();
            // Iterate over all selected checkboxes
            rows_selected = [];
            $.each(rows, function(index, rowId) {
              // Create a hidden element 
              rows_selected.push("'" + rowId + "'");
            });

            $.ajax({
              method: "POST",
              url: "./lib/ods/save_data_advance.php",
              data: 'id='+rows_selected.join(',')+'&f=<?php echo $f ?>&formType=reject_pem_usaha',
              dataType: 'json',
              success: function(msg) {
                table.ajax.reload();
                popup(msg.status, msg.msg, '');
              },
              error: function(err) {
                console.log(err);
                if (err.responseText.indexOf("duplicate key") != -1) {
                  popup('error', 'Error Insert, Data Duplicate !!', '');
                } else {
                  popup('error', err.responseText, '');
                }
              }
            });
          }
        }
      }
    ],
    select: {
      style: 'multi'
    },
    "columnDefs": [{
      'targets': 0,
      'checkboxes': {
        'selectRow': true
      }
    }]
  });

  $('.formSubmit').on('click', function() {
    var form = $('.formModal<?php echo $formName ?>').serialize();

    $.ajax({
      method: "POST",
      url: "./lib/ods/save_data_advance.php",
      data: form + '&f=<?php echo $f ?>',
      dataType: 'json',
      success: function(msg) {

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
          $('#Modal<?php echo $formName ?>').modal('toggle');
          popup('success', msg.msg, '');
        } else {
          popup('error', 'Mohon isi kolom yang sudah di sediakan');
        }
      },
      error: function(err) {
        console.log(err);
        if (err.responseText.indexOf("duplicate key") != -1) {
          popup('error', 'Error Insert, Data Duplicate !!', '');
        } else {
          popup('error', err.responseText, '');
        }
      }
    });
  })



  $('.formUpload').on('click', function() {
    $(this).html("Mohon Tunggu . . .");
    $(this).attr("disabled", true);
    var dataFrom = new FormData();

    //File data
    var file_data = $('input[type="file"]');
    for (var i = 0; i < file_data.length; i++) {
      dataFrom.append(file_data[i].name, file_data[i].files[0]);
    }

    dataFrom.append("type", "pemilik_usaha");
    dataFrom.append("f", '<?php echo $f ?>');

    $.ajax({
      method: "POST",
      url: "./lib/ods/save_upload_excel.php",
      data: dataFrom,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(msg) {

        $(".formUpload").html("Upload");
        $(".formUpload").attr("disabled", false);

        if (msg.rej == 1) {
          var blob = new Blob([msg.csv]);
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = "reject.csv";
          link.click();
        }

        alert("Informasi \n" + msg.msg);

        $('#Modal<?php echo $formName ?>').modal('hide');

      },
      error: function(err) {
        $(".formUpload").html("Upload");
        $(".formUpload").attr("disabled", false);

        console.log(err);
        // alert("Error : "+ err.responseText);
        var blob = new Blob([err.responseText]);
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = "reject.csv";
        link.click();
      }
    });
  })



  $('.actFilter2').on('click', function() {

    var query = [];
    var notFil = ['0', ''];
    <?php
    $getValFil = $adeQ->select($adeQ->prepare("select * from core_filter where idform=%s", $f));
    foreach ($getValFil as $val) {
      if ($val['logic'] == 'like') {
        echo "var value = \"'%\" + $('.fil$val[name_field]').val() + \"%'\";";
        echo "
      if(!notFil.includes($('.fil$val[name_field]').val()))
      {
        query.push('lower($val[name_field]) $val[logic] lower(' + value + ')');
      }";
      } else {
        echo "var value = \"'\" + $('.fil$val[name_field]').val() + \"'\";";
        echo "
      if(!notFil.includes($('.fil$val[name_field]').val()))
      {
        query.push('$val[name_field] $val[logic] ' + value);
      }";
      }
    }

    ?>

    $('.queryFilter').val(query.join(" and "));

    table.draw();

  })

  $('.resetFilter').on('click', function() {

    $('.queryFilter').val('');
    table.draw();

  })


  function loadForm(id, type) {
    if (id == null) {
      var val = '';
    } else {
      var val = "&v=" + id;
    }


    $.ajax({
      method: "POST",
      url: "./lib/ods/form_modal_act_ods.php?f=<?php echo $f ?>&type=" + type + val,
      success: function(msg) {
        var fData = JSON.parse(msg)

        mdlAddEdit(fData);

        //switch engine edit jika data NIK sudah ada
        var cekAction = $("[name='formType']").val();
        if (cekAction == 'add') {

          function ktpfocout() {
            $(".nik_ktp").on("focusout", function() {
              var ktp = $(this).val();

              $.ajax({
                url: "./lib/ods/form_modal_act_reverse_add_pu.php?f=<?php echo $f ?>&type=edit&ktp=" + ktp,
                dataType: "json",
                method: "POST",
                success: function(m) {

                  console.log(m);
                  if (m.statusKTP > 0) {
                    mdlAddEdit(m);
                  } else {
                    mdlAddEdit(fData);
                    $(".formModaldata_mst_pemilik_usaha .nik_ktp").val(ktp);
                  }

                  ktpfocout();
                },
                error: function(err) {
                  console.log(err);
                }
              });
            });
          };

          ktpfocout();

        }



        $('#Modal<?php echo $formName ?>').modal('show');
      },
      error: function(err) {
        console.log(err);
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
      url: "./lib/ods/form_modal_act_ods.php?f=<?php echo $f ?>&type=" + type + val,
      success: function(msg) {
        // console.log(JSON.parse(msg));
        var fData = JSON.parse(msg)
        $('#ModalText<?php echo $formName ?>').text(fData.type);
        $('.formModal<?php echo $formName ?>').html(fData.data);


        $('.datepicker').datepicker({
          format: '<?php echo $dateJS ?>',
          autoclose: true
        });

        if (type == 'search') {
          $('.formSubmit').css('display', 'none');
          $('.formUpload').css('display', 'none');
          $('.actFilter').css('display', 'inline');
        } else {
          $('.actFilter').css('display', 'none');
          $('.formUpload').css('display', 'none');
          $('.formSubmit').css('display', 'inline');
        }


        $('#Modal<?php echo $formName ?>').modal('show');
      }
    });
  }




  function formMultiValue(data, inputForm, ClassFormName) {
    var htmlMulti = '<button id="b1" type="button" class="btn add-more">Add More</button>\
      <br><br>\
      <div class="formFilter">\
      <div class="row formRow">\
        ' + inputForm + '\
      </div>\
    </div>';
    $(".dialog-multivalue").html(htmlMulti);


    var next = (data != '') ? data.split('|').length : 1;
    var formFilter = $(".formFilter").html();

    if (data != '') {
      var dtSpltTlp = data.split('|');
      for (var i = 1; i < dtSpltTlp.length; i++) {
        next = next + 1;
        var newIn = formFilter.replace("formRow", "formRow" + next);
        var newInput = $(newIn);
        var removeBtn = "<div class='col-md-2'><button data-id='" + next + "' class='btn btn-danger remove-me' >-</button></div>";
        $(".dialog-multivalue").append(newInput);
        $('.formRow' + next).append(removeBtn);
        $('.remove-me').click(function(e) {
          e.preventDefault();
          var fieldNum = $(this).data('id');
          var fieldID = ".formRow" + fieldNum;
          $(fieldID).remove();
        });
      };

      var l = 0;
      $("." + ClassFormName).each(function() {
        $(this).val(dtSpltTlp[l]);
        l++;
      });
    }

    $(".add-more").click(function(e) {
      e.preventDefault();
      next = next + 1;
      var newIn = formFilter.replace("formRow", "formRow" + next);
      var newInput = $(newIn);
      var removeBtn = "<div class='col-md-2'><button data-id='" + next + "' class='btn btn-danger remove-me' >-</button></div>";
      $(".dialog-multivalue").append(newInput);
      $('.formRow' + next).append(removeBtn);
      $('.remove-me').click(function(e) {
        e.preventDefault();
        var fieldNum = $(this).data('id');
        var fieldID = ".formRow" + fieldNum;
        $(fieldID).remove();
      });
      $("[data-mask]").inputmask();
    });
  }


  function mdlAddEdit(mData) {

    $('#ModalText<?php echo $formName ?>').text(mData.type);
    $('.formModal<?php echo $formName ?>').html(mData.data);

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
    $('.formUpload').css('display', 'none');
    $('.formSubmit').css('display', 'inline');

    //Custom Javascript add button
    $(".grpnik_ktp").removeClass("col-md-12").addClass("col-md-6");
    var btnHtml = "<div class='form-group col-md-6' style='margin-top:20px'><button type='button' class='btn btn-success btnCekKTP'>Validasi KTP</button></div><div class='row'></div>";
    $(".grpnik_ktp").after(btnHtml);


    $(".btnCekKTP").on("click", function() {
      $(this).html("Proses ... ");
      $(this).removeClass("btn-success").addClass("btn-default disabled");

      setTimeout(function() {
        popup("error", "API Disdukcapil belum tersedia", 'Informasi');

        $('.btnCekKTP').html("Validasi KTP");
        $('.btnCekKTP').removeClass("btn-default disabled").addClass("btn-success");
      }, 5000);
    });

    //radio toggle untuk pengisian yang sama dengan KTP
    var switchKtp = '<div class="grpswithKtp col-md-12" style="margin-bottom: 20px;">\
                  <label><b>Apakah alamat domisili sama dengan alamat ktp ?</b></label>\
                  <label class="switch">\
                    <input type="checkbox" class="switchKTP">\
                    <span class="slider round"></span>\
                  </label></div>';

    $(".grptlp_mobile").before(switchKtp);


    $(".tlp_mobile").prop("readonly", true);
    $(".tlp_mobile").on('click', function() {

      var dtTlp = $(".tlp_mobile").val();
      var formInputMulti = '<div class="col-md-10"><input data-inputmask="\'mask\': \'[+62999999999999]\'" data-mask type="text" name="tlpMulti[]" class="form-control tlpMulti" placeholder="Enter Tlp mobile"></div>'
      formMultiValue(dtTlp, formInputMulti, 'tlpMulti');
      $("[data-mask]").inputmask();

      dialog.dialog("open");

    });




    $("[data-mask]").inputmask();


    $('.switchKTP').click(function() {
      if ($(this).prop("checked") == true) {
        var kelurahanKTP = $(".id_kelurahan_ktp").val();
        var alamatKTP = $(".ktp_alamat").val();
        var rtKTP = $(".ktp_rt").val();
        var rwKTP = $(".ktp_rw").val();


        if (kelurahanKTP != '' && alamatKTP != '' && rtKTP != '' && rwKTP != '') {

          $.ajax({
            url: "./lib/ods/load_external_data.php?tipe=getDetailProvKel&id_kel=" + kelurahanKTP,
            dataType: "json",
            success: function(msg) {
              if (msg.data.length == 0) {
                popup('error', 'Data Provinsi, Kabupaten Kota, Kecamatan, Keluarahan Tidak Ditemukan !')
              } else {

                //REMOVE ENGINE
                $('.formModaldata_mst_pemilik_usaha .id_provinsi_domisili').prop('disabled', false);
                $('.formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili').prop('disabled', false);
                $('.formModaldata_mst_pemilik_usaha .id_kecamatan_domisili').prop('disabled', false);
                $('.formModaldata_mst_pemilik_usaha .id_kelurahan_domisili').prop('disabled', false);
                $('.formModaldata_mst_pemilik_usaha .id_provinsi_domisili').select2('destroy');
                $('.formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili').select2().select2('destroy');
                $('.formModaldata_mst_pemilik_usaha .id_kecamatan_domisili').select2().select2('destroy');
                $('.formModaldata_mst_pemilik_usaha .id_kelurahan_domisili').select2().select2('destroy');
                $(".formModaldata_mst_pemilik_usaha .domisili_rt").inputmask("remove");
                $(".formModaldata_mst_pemilik_usaha .domisili_rw").inputmask("remove");
                $(".formModaldata_mst_pemilik_usaha .domisili_kodepos").inputmask("remove");


                //SET VALUES
                $(".formModaldata_mst_pemilik_usaha .domisili_rt").val(rtKTP);
                $(".formModaldata_mst_pemilik_usaha .domisili_rw").val(rwKTP);
                $(".formModaldata_mst_pemilik_usaha .domisili_kodepos").val(msg.data[0].kode_pos);
                $(".formModaldata_mst_pemilik_usaha .domisili_alamat").val(alamatKTP);
                $('.formModaldata_mst_pemilik_usaha .id_provinsi_domisili ').html('<option value="' + msg.data[0].id_provinsi + '" selected>' + msg.data[0].nama_provinsi + '</option>');
                $('.formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili ').html('<option value="' + msg.data[0].id_kabupaten_kota + '" selected>' + msg.data[0].nama_kabupaten_kota + '</option>');
                $('.formModaldata_mst_pemilik_usaha .id_kecamatan_domisili ').html('<option value="' + msg.data[0].id_kecamatan + '" selected>' + msg.data[0].nama_kecamatan + '</option>');
                $('.formModaldata_mst_pemilik_usaha .id_kelurahan_domisili ').html('<option value="' + msg.data[0].id_kel + '" selected>' + msg.data[0].nama_kelurahan + '</option>');

                //SET READ ONLY
                $(".formModaldata_mst_pemilik_usaha .domisili_rt").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .domisili_rw").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .domisili_alamat").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .id_provinsi_domisili").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .id_kecamatan_domisili").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .id_kelurahan_domisili").attr("readonly", true);
                $(".formModaldata_mst_pemilik_usaha .domisili_kodepos").attr("readonly", true);


              }
            },
            error: function(err) {
              console.log(err);
            }
          });

        } else {
          popup('error', 'Mohon lengkapi data KTP terlebih dahulu !!');
        }
      } else {

        $(".formModaldata_mst_pemilik_usaha .domisili_alamat").val(null);
        $(".formModaldata_mst_pemilik_usaha .domisili_rt").val(null);
        $(".formModaldata_mst_pemilik_usaha .domisili_rw").val(null);
        $(".formModaldata_mst_pemilik_usaha .domisili_kodepos").val(null);
        $('.formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili ').html('<option value="" selected>Please Select Kabupaten kota domisili</option>');
        $('.formModaldata_mst_pemilik_usaha .id_kecamatan_domisili ').html('<option value="" selected>Please Select Kecamatan domisili</option>');
        $('.formModaldata_mst_pemilik_usaha .id_kelurahan_domisili ').html('<option value="" selected>Please Select Kelurahan domisili</option>');

        $(".formModaldata_mst_pemilik_usaha .domisili_rt").attr("readonly", false);
        $(".formModaldata_mst_pemilik_usaha .domisili_rw").attr("readonly", false);
        $(".formModaldata_mst_pemilik_usaha .domisili_alamat").attr("readonly", false);
        $(".formModaldata_mst_pemilik_usaha .domisili_kodepos").attr("readonly", false);
        $('.formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili').prop('disabled', true);
        $('.formModaldata_mst_pemilik_usaha .id_kecamatan_domisili').prop('disabled', true);
        $('.formModaldata_mst_pemilik_usaha .id_kelurahan_domisili').prop('disabled', true);
        $(".formModaldata_mst_pemilik_usaha .id_provinsi_domisili").attr("readonly", false);
        $(".formModaldata_mst_pemilik_usaha .id_kabupaten_kota_domisili").attr("readonly", false);
        $(".formModaldata_mst_pemilik_usaha .id_kecamatan_domisili").attr("readonly", false);
        $(".formModaldata_mst_pemilik_usaha .id_kelurahan_domisili").attr("readonly", false);

        $(".formModaldata_mst_pemilik_usaha .domisili_rt").inputmask();
        $(".formModaldata_mst_pemilik_usaha .domisili_rw").inputmask();
        $(".formModaldata_mst_pemilik_usaha .domisili_kodepos").inputmask();
        $('.formModaldata_mst_pemilik_usaha .id_provinsi_domisili').select2({
          ajax: {
            url: './lib/base/select_data.php?t=select_vw_ref_provinsi&filter=all',
            dataType: 'json',
            data: function(params) {
              return {
                search: params.term
              };
            }
          }
        });
      }
    });
  }
</script>
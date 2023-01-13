<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

$f = $_GET['f'];

$qForm = $adeQ->select($adeQ->prepare(
    "select * from core_forms where idform=%d", $f
));

$qField = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true order by id", $f
));


$qFieldSelect = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true and type_input in ('select', 'checkbox') order by id", $f
));

foreach($qForm as $valForm)
{
  $formName = $valForm['formname'];
  $formView = $valForm['formview'];
  $formDesc = $valForm['description'];
  $formCode = $valForm['formcode'];
}

//SHOW SCHEMA VIEW
$qSchemaView = $adeQ->select($adeQ->prepare(
    "select * from information_schema.columns where table_name=%s order by ordinal_position", $formView
));

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
                   $qFilter = $adeQ->select($adeQ->prepare("select * from core_filter
                                            where idform=%s
                                            and gropname='header'
                                            order by id", $f));
                   
                   $type1 = array('text', 'email', 'password', 'number');
                    $type2 = array('select');
                    $type3 = array('checkbox');
                    $type4 = array('date');

                   foreach($qFilter as $valField)
                   {
                     if(in_array($valField['type_input'], $type1))
                      {
                        echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <input type='$valField[type_input]' name='$valField[name_field]' class='form-control fil$valField[name_field]' placeholder='Enter $valField[name_input]'>
                          </div>
                        ";
                      }elseif(in_array($valField['type_input'], $type2))
                      {
                        echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
                                <option value=''>Please Select $valField[name_input]</option>
                              </select>
                          </div>
                        ";

                        
                        if($valField['case_cade'] != null)
                        {
                          $qCaseCadeFilter = $adeQ->select("select * from core_filter where id=$valField[case_cade]");
                          echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil".$qCaseCadeFilter[0]['name_field']."').on('change', function(){
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
                        }else{
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

                      }elseif(in_array($valField['type_input'], $type3))
                      {
                        echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input] </label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
                                
                              </select>
                          </div>
                        ";

                        $qCaseCadeFilter = $adeQ->select("select * from core_filter where case_cade=$valField[case_cade]");
                        if($qCaseCadeFilter)
                        {
                          echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil".$qCaseCadeFilter[0]['name_field']."').on('change', function(){
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
                        }else{
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
                      }elseif(in_array($valField['type_input'], $type4))
                      {
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

                   foreach($qFilter as $valField)
                   {
                     if(in_array($valField['type_input'], $type1))
                      {
                        echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <input type='$valField[type_input]' name='$valField[name_field]' class='form-control fil$valField[name_field]' placeholder='Enter $valField[name_input]'>
                          </div>
                        ";
                      }elseif(in_array($valField['type_input'], $type2))
                      {
                        echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input]</label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
                                <option value=''>Please Select $valField[name_input]</option>
                              </select>
                          </div>
                        ";

                        
                        if($valField['case_cade'] != null)
                        {
                          $qCaseCadeFilter = $adeQ->select("select * from core_filter where id=$valField[case_cade]");
                          echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil".$qCaseCadeFilter[0]['name_field']."').on('change', function(){
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
                        }else{
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

                      }elseif(in_array($valField['type_input'], $type3))
                      {
                        echo "
                          <div class='form-group grpFil$valField[name_field] col-md-$valField[position_md]'>
                              <label for='$valField[name_field]'>$valField[name_input] </label>
                              <select class='form-control fil$valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
                                
                              </select>
                          </div>
                        ";

                        $qCaseCadeFilter = $adeQ->select("select * from core_filter where case_cade=$valField[case_cade]");
                        if($qCaseCadeFilter)
                        {
                          echo "
                          <script>
                            $('.fil$valField[name_field]').prop('disabled', true);
                            $('.fil".$qCaseCadeFilter[0]['name_field']."').on('change', function(){
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
                        }else{
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
                      }elseif(in_array($valField['type_input'], $type4))
                      {
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
                    <input type="hidden" class="queryFilter"/>
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
                  foreach($qSchemaView as $valField)
                  {
                    if(substr($valField['column_name'],0,3) != 'id_')
                    {
                      echo "<th>".ucfirst(str_replace("_", " ", $valField['column_name']))."</th>";
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
    format: '<?php echo $dateJS?>',
    autoclose: true
  });


$('.filterAdvCheck').click(function(){
    if($(this).prop("checked") == true){
        $('.advanceFilter').css('display', 'block');
    }else{
        $('.advanceFilter').css('display', 'none');
    }
});

//init dialog
dialog = $( ".dialog-multivalue" ).dialog({
    autoOpen: false,
    height: 400,
    width: 450,
    title: "Add Media Sosial",
    appendTo: "#Modal<?php echo $formName ?>",
    modal: true,
    buttons: {
      "Add": function(){
        var akun = new Array(); 
        var media = new Array(); 
        $('.reffOnline').each(function() {
          media.push($(this).val());
        });
        $('.akunMedsos').each(function() {
          akun.push($(this).val());
        });

        $(".media_sosial").val(media.join('|'));
        $(".nama_akun_media_sosial").val(akun.join('|'));
        $(".media_sosial_form").val('Sudah Terisi');

        dialog.dialog( "close" );
      },
      Cancel: function() {
        dialog.dialog( "close" );
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
        "url" : "./lib/ods/load_data_ods_area.php?t=<?php echo $formView ?>&f=<?php echo $f?>",
        "type": "POST",
        "data" : function(data){
          var dtQuery = $(".queryFilter").val();
          data.query = dtQuery;
        }
      },
      "searching": false,
      "scrollX": true,
      scrollCollapse: true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "columns": [
      <?php
      foreach($qSchemaView as $valField)
      {
        if(substr($valField['column_name'],0,3) != 'id_')
        {
          echo "{ data: '$valField[column_name]', width:'150px' },";
        }
      }
      ?>
      ],
      buttons: [
          {
            text: '<i class="fa fa-plus-circle"></i> Add',
            action: function ( e, dt, node, config ) {
              loadForm(null, "add"); 

            }
          },
          {
            text: '<i class="fa fa-pencil-square-o"></i> Edit',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Mohon pilih data terlebih dahulu');
              }else{
                loadForm(rowData.id, "edit");
              }

              
            }
          }
        ],
        select: {
            style: 'single'
        },
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ]
    });

$('.formSubmit').on('click', function(){
   var form = $('.formModal<?php echo $formName ?>').serialize();
   
   $.ajax({
      method: "POST",
      url: "./lib/umkm/save_data_advance_umkm.php",
      data: form + '&f=<?php echo $f ?>',
      dataType: 'json',
      success: function( msg ) {
        $.each(msg.validate, function (index, value){
             if(value.err == 'validate')
             {
               $('.grp'+value.field).removeClass( "has-error" ).addClass( "has-error" );
               $('.err'+value.field).html(value.msg);
             }else{
               $('.grp'+value.field).removeClass( "has-error" );
               $('.err'+value.field).html(null);
             }
          })

        if(msg.status)
        {
          table.ajax.reload();
          $('#Modal<?php echo $formName ?>').modal('toggle');
          popup('success', msg.msg, '');
        }else{
          popup('error', 'Mohon isi kolom yang sudah di sediakan');
        }
      },
      error: function(err){
        if(err.responseText.indexOf("duplicate key") != -1){
          popup('error', 'Error Insert, Data Duplicate !!', '');
        }else{
          popup('error', err.responseText, '');
        }
      }
    }); 
})


$('.formUpload').on('click', function(){
    $(this).html("Mohon Tunggu . . .");
    $(this).attr("disabled", true);
    var dataFrom = new FormData();

    //File data
    var file_data = $('input[type="file"]');
    for (var i = 0; i < file_data.length; i++) {
        dataFrom.append(file_data[i].name, file_data[i].files[0]);
    }

    dataFrom.append("type", "usaha");
    dataFrom.append("f", '<?php echo $f ?>');
   
   $.ajax({
    method: "POST",
    url: "./lib/ods/save_upload_excel.php",
    data: dataFrom,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function( msg ) {

        $(".formUpload").html("Upload");
        $(".formUpload").attr("disabled", false);

        if(msg.rej == 1){
          var blob=new Blob([msg.csv]);
          var link=document.createElement('a');
          link.href=window.URL.createObjectURL(blob);
          link.download="reject.csv";
          link.click();
        }


        alert("Informasi \n" + msg.msg);
        $('#Modal<?php echo $formName ?>').modal('hide');
    },
    error: function(err){
      $(".formUpload").html("Upload");
      $(".formUpload").attr("disabled", false);
        
        console.log(err);
        // alert("Error : "+ err.responseText);
        
        var blob=new Blob([err.responseText]);
        var link=document.createElement('a');
        link.href=window.URL.createObjectURL(blob);
        link.download="reject.csv";
        link.click();
    }
    });  
})



$('.actFilter2').on('click', function(){

  var query = [];
  var notFil = ['0', ''];
  <?php
  $getValFil = $adeQ->select($adeQ->prepare("select * from core_filter where idform=%s", $f));
  foreach($getValFil as $val)
  {
    if($val['logic'] == 'like')
    {
      echo "var value = \"'%\" + $('.fil$val[name_field]').val() + \"%'\";";
      echo "
      if(!notFil.includes($('.fil$val[name_field]').val()))
      {
        query.push('lower($val[name_field]) $val[logic] lower(' + value + ')');
      }";
    }else{
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

$('.resetFilter').on('click', function(){

  $('.queryFilter').val('');
  table.draw();
  
})


function loadForm(id, type)
{
  if(id == null)
  {
    var val = '';
  }else
  {
    var val = "&v="+id;
  }


  $.ajax({
    method: "POST",
    url: "./lib/ods/form_modal_act_ods.php?f=<?php echo $f ?>&type="+type+val,
    success: function( msg ) {
        var fData = JSON.parse(msg)
        $('#ModalText<?php echo $formName ?>').text(fData.type);
        $('.formModal<?php echo $formName ?>').html(fData.data);

        $('.datepicker').datepicker({
          format: '<?php echo $dateJS?>',
          autoclose: true
        });

        <?php
        foreach ($qFieldSelect as $select ) {
          if($select['case_cade'] != null)
          {
            $qCaseCade = $adeQ->select($adeQ->prepare("select * from core_fields where id_form=%d and id=%d", $f, $select['case_cade']));
            foreach($qCaseCade as $caseCade)
            {
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
          }else{
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


        $("[data-mask]").inputmask();

        
        //Custom Javascript add button
        $(".grpnib").removeClass("col-md-12").addClass("col-md-6");
        var btnHtml = "<div class='form-group col-md-6' style='margin-top:20px'><button type='button' class='btn btn-success btnCekNIB'>Validasi NIB</button></div><div class='row'></div>";
        $(".grpnib").after(btnHtml);

        $(".btnCekNIB").on("click", function(){
          $(this).html("Proses ... ");
          $(this).removeClass("btn-success").addClass("btn-default disabled");
          
          setTimeout(function() {
            popup("error", "API OSS belum tersedia", 'Informasi');

            $('.btnCekNIB').html("Validasi KTP");
            $('.btnCekNIB').removeClass("btn-default disabled").addClass("btn-success");
          }, 5000);
        });

        //change UI NPWP mandetory or not
        $(".id_bentuk_usaha ").on("change", function(){
            var idB = $(this).val();
            if(idB == 8){
                $(".grpnpwp_usaha label span").html("");
            }else{
                $(".grpnpwp_usaha label span").html("*");
            }
        });


        
        <?php
          $refOnline = $adeQ->select("select * from select_vw_ref_platform_online");
          echo "var dtReffOnline = " . json_encode($refOnline);
        ?>

        $(".media_sosial").after('<input type="text" name="media_sosial_form" value="" class="form-control media_sosial_form" placeholder="Enter Media sosial" readonly>');
        $(".media_sosial").prop("readonly", true).css('display', 'none');
        $(".grpnama_akun_media_sosial ").css('display', 'none');
        $(".nama_akun_media_sosial").prop("readonly", true);
        $(".media_sosial_form").on('click', function(){
          var dtMedsos = $(".media_sosial").val();
          var nama_akun_media_sosial = $(".nama_akun_media_sosial").val();
          
          var reffForm = '<option value="">-- Pilih Media Sosial --</option>';
          $.each(dtReffOnline, function(idx, val){
            reffForm += '<option value="'+val.id+'">'+val.text+'</option>';
          });

          var formInputMulti = '<div class="col-md-5">\
            <select type="text" class="form-control reffOnline">'+reffForm+'</select>\
          </div>\
          <div class="col-md-5">\
            <input type="text" class="form-control akunMedsos" placeholder="Enter Akun Media Sosial">\
          </div>';

          formMultiValue(dtMedsos, nama_akun_media_sosial, formInputMulti, 'reffOnline', 'akunMedsos');
          $("[data-mask]").inputmask();
          dialog.dialog( "open" );
        });


        $('#Modal<?php echo $formName ?>').modal('show');
    },
      error: function(err){
        console.log(err);
      }
  }); 
}




function loadFormTw(id, type)
{
  if(id == null)
  {
    var val = '';
  }else
  {
    var val = "&v="+id;
  }

  $.ajax({
    method: "POST",
    url: "./lib/ods/form_modal_act_ods.php?f=<?php echo $f ?>&type="+type+val,
    success: function( msg ) {
       // console.log(JSON.parse(msg));
        var fData = JSON.parse(msg)
        $('#ModalText<?php echo $formName ?>').text(fData.type);
        $('.formModal<?php echo $formName ?>').html(fData.data);

        $('.datepicker').datepicker({
          format: '<?php echo $dateJS?>',
          autoclose: true
        });        

        if(type == 'search')
        {
          $('.formSubmit').css('display', 'none');
          $('.formUpload').css('display', 'none');
          $('.actFilter').css('display', 'inline');
        }else
        {
          $('.actFilter').css('display', 'none');
          $('.formUpload').css('display', 'none');
          $('.formSubmit').css('display', 'inline');
        }


        $('#Modal<?php echo $formName ?>').modal('show');
    }
  }); 
}




function formMultiValue(data, data2, inputForm, ClassFormName, ClassFormName2)
{
    var htmlMulti = '<button id="b1" type="button" class="btn add-more">Add More</button>\
      <br><br>\
      <div class="formFilter">\
      <div class="row formRow">\
        '+inputForm+'\
      </div>\
    </div>';
    $(".dialog-multivalue").html(htmlMulti);


    var next = (data != '') ? data.split('|').length : 1 ;
    var formFilter = $(".formFilter").html();

    if(data != ''){
      var dataSpl = data.split('|');
      var dataSpl2 = data2.split('|');
      for(var i=1; i < dataSpl.length; i++){
        next = next + 1;
        var newIn = formFilter.replace("formRow", "formRow"+next);
        var newInput = $(newIn);
        var removeBtn = "<div class='col-md-2'><button data-id='"+next+"' class='btn btn-danger remove-me' >-</button></div>";
        $(".dialog-multivalue").append(newInput);
        $('.formRow'+next).append(removeBtn);
        $('.remove-me').click(function(e){
            e.preventDefault();
            var fieldNum = $(this).data('id');
            var fieldID = ".formRow" + fieldNum;
            $(fieldID).remove();
        });
      };

      var l = 0;
      $("."+ClassFormName).each(function(){
          $(this).val(dataSpl[l]);
          l++;
      });

      l = 0;
      $("."+ClassFormName2).each(function(){
          $(this).val(dataSpl2[l]);
          l++;
      });
    }

    $(".add-more").click(function(e){
        e.preventDefault();
        next = next + 1;
        var newIn = formFilter.replace("formRow", "formRow"+next);
        var newInput = $(newIn);
        var removeBtn = "<div class='col-md-2'><button data-id='"+next+"' class='btn btn-danger remove-me' >-</button></div>";
        $(".dialog-multivalue").append(newInput);
        $('.formRow'+next).append(removeBtn);
        $('.remove-me').click(function(e){
            e.preventDefault();
            var fieldNum = $(this).data('id');
            var fieldID = ".formRow" + fieldNum;
            $(fieldID).remove();
        });
        $("[data-mask]").inputmask();
    });
}




function imageInit(cssimg, cssInptFile, cssinfo){
  cssInptFile.css('display', 'none');
  cssimg.on("click", function(){
    cssInptFile.click();
    var img = $(this);
    cssInptFile.on("change", function(e){
      var file = e.originalEvent.srcElement.files[0];
      
      //security
      console.log(file);
      if(
        file.type.substring(0,4) == 'text' || file.type == ''
      ){
        popup('error', 'File Not Permission !!!', '');
        cssInptFile.val('');
      }else{
        var reader = new FileReader();
        reader.onloadend = function() {
          if (file.type == 'application/pdf'){
            img.attr("src", 'assets/img/pdf_icon2.png');
          }else if(file.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
            img.attr("src", 'assets/img/excel_icon.png');
          }else{
            img.attr("src", reader.result);
          }

          cssinfo.html(file.name);
        }
      }

      
      reader.readAsDataURL(file);
  });
  });
}

</script>
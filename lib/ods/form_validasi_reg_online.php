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

//GET STRUCTURE API DUKCAPIL
$qDukcapilApiKey = $adeQ->select(
  "select * from core_rest_api ap
  inner join core_fields f on ap.id_core_field=f.id
  where f.id_form=47"
);

$getFieldApi = "";
$colorGetApi = "";
foreach($qDukcapilApiKey as $keyApi){
  if($keyApi['key'] == 'pk'){
    $hostApiDukcapil = $keyApi['url_api'];
    $keyField = $keyApi['name_field'];
    $keyFieldApi = $keyApi['field_api'];
  }else{
    $inputApi = "<input type='hidden' name='api$keyApi[name_field]-$keyApi[id_form]' value='\"+msg.$keyApi[format_json].$keyApi[field_api]+\"'/>";
    $getFieldApi .= '$(".api'.$keyApi['name_field'].'-'.$keyApi['id_form'].'").html("<b>"+msg.'.$keyApi['format_json'].'.'.$keyApi['field_api'].' + "</b> (<i>data '.$keyApi['name_api'].'</i>)'.$inputApi.'");';
    $colorGetApi .= '$(".tr'.$keyApi['name_field'].'-'.$keyApi['id_form'].'").css("background", "yellow");';
  }
}


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
        <button type="button" class="btn btn-warning formValidasi">Validasi</button>
        <button type="button" class="btn btn-info formReplace">Replace Data</button>
        <button type="button" class="btn btn-success formVerifikasi">Verifikasi</button>
        <button type="button" class="btn btn-danger formReject">Reject</button>
      </div>
    </div>
  </div>
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


    var table = $("#<?php echo $formName ?>").DataTable({
      "dom": 'Bltip',
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url" : "./lib/ods/load_data_ods_post.php?t=<?php echo $formView ?>&f=<?php echo $f?>",
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
            text: '<i class="fa fa-sticky-note-o"></i> Check Data',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Mohon pilih data terlebih dahulu');
              }else{
                viewData(rowData.id, "47,48");
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

$('.formValidasi').on('click', function(){
   var idKtp = table.rows(".selected").data()[0].<?php echo $keyField?>;
   $(this).removeClass("btn-success").addClass("btn-default disabled");
   $(this).html("Proses ...");
   
   $.ajax({
      method: "POST",
      url: "<?php echo $hostApiDukcapil ?>",
      data: "<?php echo $keyFieldApi?>=" + idKtp,
      dataType: 'json',
      success: function( msg ) {

        $('.formValidasi').html("Validasi KTP");
        $('.formValidasi').removeClass("btn-default disabled").addClass("btn-warning");

        if(msg.status == 'success')
        {
          <?php echo $getFieldApi . $colorGetApi?>

          
          
          $(".formReplace").prop('disabled', false);
          $(".formVerifikasi").prop('disabled', false);
          $(".formReject").prop('disabled', false);

          popup("success", 'Validasi dengan API berhasil di lakukan, harap di periksa kembali sebelum di verifikasi', '');

        }else{
          popup("error", 'API Not Respon', '');
        }
      },
      error: function(err){
        console.log(err);
        popup('error', 'Error API', '');
      }
    }); 
})



$('.formReplace').on('click', function(){
   var id = table.rows(".selected").data()[0].id;
   var form = $('.formModal<?php echo $formName ?>').serialize();
   $(this).removeClass("btn-info").addClass("btn-default disabled");
   $(this).html("Proses ...");

   $.ajax({
      method: "POST",
      url: "./lib/ods/replace_data_validasi_api.php",
      data: form + "&f=47&id=" + id,
      dataType: 'json',
      success: function( msg ) {
        $('.formReplace').html("Replace Data");
        $('.formReplace').removeClass("btn-default disabled").addClass("btn-info");

        if(msg.status == 'success')
        {
          
          
          
        }

        popup(msg.status, msg.msg, '');
      },
      error: function(err){
        console.log(err);
        popup('error', 'Error Database', '');
      }
    }); 
})



$('.formVerifikasi').on('click', function(){
   var id = table.rows(".selected").data()[0].id;
   $(this).removeClass("btn-success").addClass("btn-default disabled");
   $(this).html("Proses ...");

   $.ajax({
      method: "POST",
      url: "./lib/ods/validasi_and_reject_reg_online.php",
      data: "type=vervikasi&id=" + id,
      dataType: 'json',
      success: function( msg ) {

        $('.formVerifikasi').html("Verfikasi");
        $('.formVerifikasi').removeClass("btn-default disabled").addClass("btn-success");

        if(msg.status == 'success')
        {
          
          $('#Modal<?php echo $formName ?>').modal('toggle');
          table.draw();
          
        }

        popup(msg.status, msg.msg, '');
      },
      error: function(err){
        console.log(err);
        popup('error', 'Error Database', '');
      }
    }); 
})



$('.formReject').on('click', function(){
   var id = table.rows(".selected").data()[0].id;
   $(this).removeClass("btn-danger").addClass("btn-default disabled");
   $(this).html("Proses ...");

   $.ajax({
      method: "POST",
      url: "./lib/ods/validasi_and_reject_reg_online.php",
      data: "type=reject&id=" + id,
      dataType: 'json',
      success: function( msg ) {

        $('.formVerifikasi').html("Reject");
        $('.formVerifikasi').removeClass("btn-default disabled").addClass("btn-danger");

        if(msg.status == 'success')
        {
          
          $('#Modal<?php echo $formName ?>').modal('toggle');
          table.draw();
          
        }

        popup(msg.status, msg.msg, '');
      },
      error: function(err){
        console.log(err);
        popup('error', 'Error Database', '');
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


function viewData(idReg, idForm)
{
  $.ajax({
    method: "POST",
    url: "./lib/ods/load_validasi_reg_online.php?idForm="+idForm+"&idReg="+idReg,
    dataType: 'json',
    success: function( msg ) {
       $(".formModal<?php echo $formName ?>").html(msg.data);
       $("#ModalText<?php echo $formName ?>").html("Data Detail Registrasi Online");
       $(".formReplace").prop('disabled', true);
       $(".formVerifikasi").prop('disabled', true);
       $(".formReject").prop('disabled', true);

        $('#Modal<?php echo $formName ?>').modal('show');
    },
      error: function(err){
        console.log(err);
      }
  }); 
}


</script>
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
        <button type="button" class="btn btn-primary actFilter">Filter</button>
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
        "url" : "./lib/ods/load_data_ods_ktp.php?t=<?php echo $formView ?>&f=<?php echo $f?>",
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
          },
          {
            text: '<i class="fa fa-trash-o"></i> Delete',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Mohon pilih data terlebih dahulu');
              }else{
                loadFormTw(rowData.id, "delete");
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
    url: "./lib/ods/validasi.php",
    method: "POST",
    data: form + '&f=<?php echo $f ?>',
    dataType: 'json',
    success: function( msg ) {
        if(msg.status){
          $.ajax({
          method: "POST",
          url: "./lib/ods/save_data_advance.php",
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
            console.log(err);
            if(err.responseText.indexOf("duplicate key") != -1){
              popup('error', 'Error Insert, Data Duplicate !!', '');
            }else{
              popup('error', err.responseText, '');
            }
          }
        }); 
        }
    },
    error: function(err){
      console.log(err);
    }
  })

  
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
        $('.formSubmit').css('display', 'inline');

       
        //cek no tlp di pemilik usaha
        var idPmilikUshaInit = $(".id_pemilik_usaha").val();
        $(".grpid_pemilik_usaha ").before('<span class="col-md-12 infoValidateTlp" style="color:red;margin-bottom: 10px;"></span>');
        $.ajax({
          url : './lib/base/select_data.php?t=check_tlp_mobile_pemilik_usaha&filter=' + idPmilikUshaInit,
          dataType: 'json',
          success: function(msg){
            if(msg.results[1].text == ''){
              $(".formModaldata_trx_penerimaan_bantuan_usaha input").prop('disabled', true);
              $(".formModaldata_trx_penerimaan_bantuan_usaha select").prop('disabled', true);
              $(".formModaldata_trx_penerimaan_bantuan_usaha .id_pemilik_usaha").prop('disabled', false);
              $(".formModaldata_trx_penerimaan_bantuan_usaha [name=formType]").prop('disabled', false);
              $(".infoValidateTlp ").html('* No Telp Mobile pada data pemilik usaha wajib di isi, jika ingin mengisi data bantuan');
            }
          },
          error: function(err){
            console.log(err);
          }
        });

        $(".id_pemilik_usaha").on("change", function(){
          $.ajax({
            url : './lib/base/select_data.php?t=check_tlp_mobile_pemilik_usaha&filter=' + $(this).val(),
            dataType: 'json',
            success: function(msg){
              if(msg.results[1].text == ''){
                $(".formModaldata_trx_penerimaan_bantuan_usaha input").prop('disabled', true);
                $(".formModaldata_trx_penerimaan_bantuan_usaha select").prop('disabled', true);
                $(".formModaldata_trx_penerimaan_bantuan_usaha .id_pemilik_usaha").prop('disabled', false);
                $(".formModaldata_trx_penerimaan_bantuan_usaha [name=formType]").prop('disabled', false);
                $(".infoValidateTlp ").html('* No Telp Mobile pada data pemilik usaha wajib di isi, jika ingin mengisi data bantuan');
              }else{
                $(".formModaldata_trx_penerimaan_bantuan_usaha input").prop('disabled', false);
                $(".formModaldata_trx_penerimaan_bantuan_usaha select").prop('disabled', false);
                $(".infoValidateTlp ").html('');
              }
            },
            error: function(err){
              console.log(err);
            }
          });
        });

        


        $("[data-mask]").inputmask();


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
        dynamicSearch(fData.data);

        $('.datepicker').datepicker({
          format: '<?php echo $dateJS?>',
          autoclose: true
        });        

        if(type == 'search')
        {
          $('.formSubmit').css('display', 'none');
          $('.actFilter').css('display', 'inline');
        }else
        {
          $('.actFilter').css('display', 'none');
          $('.formSubmit').css('display', 'inline');
        }


        $('#Modal<?php echo $formName ?>').modal('show');
    }
  }); 
}




function dynamicSearch(form)
{
    var next = 1;
    var i = 1;
    var formFilter = $(".formFilter").html();
    $(".add-more").click(function(e){
        e.preventDefault();
        next = next + 1;
        var newIn = formFilter.replace("formRow", "formRow"+next);
        var newInput = $(newIn);
        var removeBtn = "<div class='col-md-2'><button data-id='"+next+"' class='btn btn-danger remove-me' >-</button></div>";
        $(".formFilter").after(newInput);
        $('.formRow'+next).append(removeBtn);
        $('.remove-me').click(function(e){
            e.preventDefault();
            var fieldNum = $(this).data('id');
            var fieldID = ".formRow" + fieldNum;
            $(fieldID).remove();
        });
    });
}
</script>
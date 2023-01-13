<?php

require "../../config.php";
require "../base/db.php";
require "security_register.php";

if(isset($_SESSION['token'])){
    $qCekReg = $adeQ->select($adeQ->prepare(
      "
        select * from reg_online.data_trx_registrasi_online
        where md5(email || '-' || nik_ktp) = %s limit 1
      ", $_SESSION['token']
    ));
    
    if (count($qCekReg) > 0 ){
      $qUpdValidasiMail = $adeQ->query($adeQ->prepare("
        update reg_online.data_trx_registrasi_online set validasi_email = true
        where md5(email || '-' || nik_ktp) = %s
      ", $_SESSION['token'] ));
    
      if($qUpdValidasiMail){
          $_SESSION['idRegister'] = $qCekReg[0]['id'];
          $_SESSION['nik_ktp'] = $qCekReg[0]['nik_ktp'];
      }



$f = $_GET['f'];

$qForm = $adeQ->select(
    "select * from core_forms where idform in ($f) order by idform"
);

$type1 = array('text', 'email', 'password', 'number');
$type2 = array('select');
$type3 = array('checkbox');
$type4 = array('date');


?>

<div class="container">
   <section class="content">
    <div class="row">
      <div class="col-xs-12"> 
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row text-center" style="margin-bottom:20px">
                  <img src="<?php echo $dir ?>assets/img/logo.png" style="width:100px" alt="IMG">
              </div>
              <form class="formModalRegOnline" action='#' method='post'>

              <?php

                foreach($qForm as $valForm)
                {
                  $formName = $valForm['formname'];
                  $formCode = $valForm['formcode'];
                  $formDesc = $valForm['description'];

                  $qGroupField = $adeQ->select($adeQ->prepare(
                    "select distinct groupname from core_fields where id_form=%d and active is true and groupname is not null", $valForm['idform']
                  ));


                    $form = '';

                    //create slide group expect header\
                    $formHeader = '';
                    $groupForm = "
                    <div class='row col-md-12'>
                      <div class='nav-tabs-custom'>
                      <ul class='nav nav-tabs'>
                    ";
                    foreach($qGroupField as $group)
                    {
                      if($group['groupname'] != 'header')
                      {
                        $groupForm .= "
                          <li class='nav-item'>
                              <a class='nav-link' id='$group[groupname]-tab' data-toggle='tab' href='#$group[groupname]' role='tab' aria-controls='$group[groupname]' aria-selected='true'>$group[groupname]</a>
                          </li>
                        ";
                      }
                    }
                    $groupForm .= "
                        </ul>
                        <div class='tab-content'>
                    ";


                    foreach($qGroupField as $group)
                    {
                      $qField = $adeQ->select($adeQ->prepare(
                          "select * from core_fields where id_form=%d and groupname=%s and active is true order by id", $valForm['idform'], $group['groupname']
                      ));

                      if($group['groupname'] == 'header')
                      {
                        $formHeader .= "<div class='row group-$group[groupname]'>";

                        foreach($qField as $valField)
                          {
                            if($valField['type_field'] == 'nm')
                            {

                              $mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

                              $descField = str_replace("_", " ", $valField['name_field']);
                              $descField = str_replace("id ", "", $descField);
                              $descField = ucfirst($descField);
                              $mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
                              
                              $default = null;
                              $readOnly = null;

                              if(in_array($valField['type_input'], $type2)){
                                $default = "<option value=''>Please Select $descField</option>";
                              }

                              if($valField['name_field'] == 'nik_ktp'){
                                  $default = $qCekReg[0]['nik_ktp'];  
                                  $readOnly = "readonly";
                              }




                              if(in_array($valField['type_input'], $type1))
                              {

                                $formHeader .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <input $readOnly $mask type='$valField[type_input]' name='$valField[name_field]-$valField[id_form]' value='$default' class='form-control $valField[name_field]' placeholder='Enter $descField'>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }elseif(in_array($valField['type_input'], $type2))
                              {
                                $formHeader .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <select class='form-control $valField[name_field]' name='$valField[name_field]-$valField[id_form]' style='width: 100%;'>
                                      $default
                                          </select>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }elseif(in_array($valField['type_input'], $type3))
                              {
                                $formHeader .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <select class='form-control $valField[name_field]' name='$valField[name_field]-$valField[id_form][]' multiple='multiple' style='width: 100%;'>
                                            
                                          </select>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }elseif(in_array($valField['type_input'], $type4))
                              {
                                $formHeader .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <input type='text' class='datepicker form-control $valField[name_field]' value='$default' name='$valField[name_field]-$valField[id_form]' placeholder='Enter $descField'>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }
                            }
                          }
                        //close group	
                        $formHeader .= '</div>';

                      }else{

                        $groupForm .= "<div class='tab-pane row' id='$group[groupname]'>";

                        foreach($qField as $valField)
                          {
                            if($valField['type_field'] == 'nm')
                            {

                              $mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

                              $descField = str_replace("_", " ", $valField['name_field']);
                              $descField = str_replace("id ", "", $descField);
                              $descField = ucfirst($descField);
                              $mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
                              $default = null;
                              $readOnly = null;

                              if(in_array($valField['type_input'], $type2)){
                                $default = "<option value=''>Please Select $descField</option>";
                              }

                              if($valField['name_field'] == 'nik_ktp'){
                                  $default = $qCekReg[0]['nik_ktp'];  
                                  $readOnly = "readonly";
                              }


                              if(in_array($valField['type_input'], $type1))
                              {
                                $groupForm .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <input $readOnly $mask type='$valField[type_input]' name='$valField[name_field]-$valField[id_form]' value='$default' class='form-control $valField[name_field]' placeholder='Enter $descField'>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }elseif(in_array($valField['type_input'], $type2))
                              {
                                $groupForm .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <select class='form-control $valField[name_field]' name='$valField[name_field]-$valField[id_form]' style='width: 100%;'>
                                            $default
                                          </select>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }elseif(in_array($valField['type_input'], $type3))
                              {
                                $groupForm .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <select class='form-control $valField[name_field]' name='$valField[name_field]-$valField[id_form][]' multiple='multiple' style='width: 100%;'>
                                            
                                          </select>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }elseif(in_array($valField['type_input'], $type4))
                              {
                                $groupForm .= "
                                  <div class='form-group grp$valField[name_field]-$valField[id_form] col-md-$valField[position_md]'>
                                          <label for='$valField[name_field]'>$descField $mustBeVall</label>
                                          <input type='text' class='datepicker form-control $valField[name_field]' value='$default' name='$valField[name_field]-$valField[id_form]' placeholder='Enter $descField'>
                                          <span class='help-block err$valField[name_field]-$valField[id_form]'></span>
                                      </div>
                                ";
                              }
                            }
                          }
                        //close group	
                        $groupForm .= '</div>';
                      }

                    }

                    $groupForm .= '</div></div></div>'; //close content tabs
                    $form .= $formHeader;
                    $form .= $groupForm;

                    echo "
                    <div class='row'></div>
                    <div class='callout callout-success'>
                      <h3 style='margin:0' class='text-center'> <b> $formDesc </b> </h3>
                    </div>
                    ";
                    echo $form;
                }

              ?>
                  
              </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="button" class="btn btn-lg btn-primary btn-block formSubmit">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </section>  
</div>



<!-- Modal Info Data -->
<div class="modal fade" id="ModalInfo" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="box-body text-center infoMsg">
              <h3>Mohon Tunggu</h3>
              <div class="overlay" style="font-size: 40px;text-align:center;"><i class="fa fa-refresh fa-spin"></i></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

$('.datepicker').datepicker({
    format: '<?php echo $dateJS?>',
    autoclose: true
  });

<?php

foreach($qForm as $valForm)
{
  $qFieldSelect = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form = %d and active is true and type_input in ('select', 'checkbox') order by id", $valForm['idform']
  ));
  
  
    foreach ($qFieldSelect as $select ) {
      if($select['case_cade'] != null)
      {
        $qCaseCade = $adeQ->select($adeQ->prepare("select * from core_fields where id_form=%d and id=%d", $valForm['idform'], $select['case_cade']));
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

}


?>

$("[data-mask]").inputmask();

$('.formSubmit').on('click', function(){
   $("#ModalInfo").modal('show');
   var form = $('.formModalRegOnline').serialize();
   
   $.ajax({
      method: "POST",
      url: "<?php echo $dir ?>lib/ods/save_data_registrasi_online.php",
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
          $(".infoMsg").html(msg.msg);

          $('#ModalInfo').on('hidden.bs.modal', function (e) {
              window.location.href = "<?php echo $dir ?>register.php";
          })
        }else{
          $("#ModalInfo").modal('toggle');
          popup('error', msg.msg, '');
        }

      },
      error: function(err){
        console.log(err);
        popup('error', err.responseText, '');
      }
    }); 
})

</script>

<?php

  }else{
    echo "
      <div class='col-md-12 text-center'>
          <h1> URL NOT FOUND !! </h1>
      </div>
    ";
  }
}else{
  echo "
      <div class='col-md-12 text-center'>
          <h1> URL NOT FOUND !! </h1>
      </div>
    ";
}

?>
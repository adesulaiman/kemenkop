<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login_global.php";

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

?>
<h3 class="text-center"><?php echo $formDesc ?></h3>
<div id="containerPage">
    <form class="formModal<?php echo $formName ?>" action='#' method='post'>

    </form>
</div>


<div class="dialog-multivalue box-body">
</div>

<script>

      
    dialog = $(".dialog-multivalue").dialog({
        autoOpen: false,
        height: 400,
        width: 450,
        title: "Add Media Sosial",
        modal: true,
        buttons: {
            "Add": function() {
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

                dialog.dialog("close");
            },
            Cancel: function() {
                dialog.dialog("close");
            }
        },
        close: function() {

        }
    });

   




    $.ajax({
        method: "POST",
        url: "./lib/umkm/form_navigation_act_ods.php?f=<?php echo $f ?>&type=add",
        success: function(msg) {
            var fData = JSON.parse(msg)
            // $('#ModalText<?php echo $formName ?>').text(fData.type);
            $('.formModal<?php echo $formName ?>').html(fData.data);

            $(".nik_ktp").val("<?php echo $_SESSION['userid'] ?>");
            $(".ktp_nama").val("<?php echo $_SESSION['username'] ?>");
            $(".nik_ktp").attr("readonly", true);

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


            //Custom Javascript add button
            $(".grpnib").removeClass("col-md-12").addClass("col-md-6");
            var btnHtml = "<div class='form-group col-md-6' style='margin-top:20px'><button type='button' class='btn btn-success btnCekNIB'>Validasi NIB</button></div><div class='row'></div>";
            $(".grpnib").after(btnHtml);

            $(".btnCekNIB").on("click", function() {
                $(this).html("Proses ... ");
                $(this).removeClass("btn-success").addClass("btn-default disabled");

                setTimeout(function() {
                    popup("error", "API OSS belum tersedia", 'Informasi');

                    $('.btnCekNIB').html("Validasi KTP");
                    $('.btnCekNIB').removeClass("btn-default disabled").addClass("btn-success");
                }, 5000);
            });

            //change UI NPWP mandetory or not
            $(".id_bentuk_usaha ").on("change", function() {
                var idB = $(this).val();
                if (idB == 8) {
                    $(".grpnpwp_usaha label span").html("");
                } else {
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
            $(".media_sosial_form").on('click', function() {
                var dtMedsos = $(".media_sosial").val();
                var nama_akun_media_sosial = $(".nama_akun_media_sosial").val();

                var reffForm = '<option value="">-- Pilih Media Sosial --</option>';
                $.each(dtReffOnline, function(idx, val) {
                    reffForm += '<option value="' + val.id + '">' + val.text + '</option>';
                });

                var formInputMulti = '<div class="col-md-5">\
            <select type="text" class="form-control reffOnline">' + reffForm + '</select>\
          </div>\
          <div class="col-md-5">\
            <input type="text" class="form-control akunMedsos" placeholder="Enter Akun Media Sosial">\
          </div>';

                formMultiValue(dtMedsos, nama_akun_media_sosial, formInputMulti, 'reffOnline', 'akunMedsos');
                $("[data-mask]").inputmask();
                dialog.dialog("open");
            });



            $("[data-mask]").inputmask();
            var paggingPage = new PagingNavigation('#containerPage');

            var func = function() {
                var form = $('.formModal<?php echo $formName ?>').serialize();
                $.ajax({
                    method: "POST",
                    url: "./lib/umkm/save_data_advance.php",
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

                        console.log(msg);

                        if (msg.status) {
                            popup('success', msg.msg, '');
                            alert("Terima kasih sudah mendaftarkan data diri dan UMKM anda, data anda akan segera kami verifikasi !!, Terima Kasih ");
                            window.location.href = "index.php";
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

            };

            paggingPage.setFuncFinish(func);


        },
        error: function(err) {
            console.log(err);
        }
    });










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

</script>
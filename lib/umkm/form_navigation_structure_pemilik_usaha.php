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
            width: 350,
            title: "Add Tlp Mobile",
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
                            HtmlLoad('./lib/umkm/form_navigation_structure_usaha.php?f=26', 'Usaha');
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
</script>
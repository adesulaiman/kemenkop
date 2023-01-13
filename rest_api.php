<?php
require "config.php";
require "lib/base/db.php";

$q = $adeQ->select($adeQ->prepare("
    select 
    nik_ktp,
    ktp_nama,
    ktp_tgl_lahir,
    ktp_jenis_kelamin,
    ktp_alamat,
    ktp_rt,
    ktp_rw,
    ktp_pekerjaan,
    id_kabupaten_kota_ktp_tempat_lahir,
    id_kecamatan_ktp,
    id_kelurahan_ktp,
    id_ktp_agama
    from vw_detail_pemilik_usaha_reg_online
    where nik_ktp = %s", $_POST['nik_ktp']
  ));

$data = array();

foreach($q as $v){
  array_push($data, $v);
}

echo json_encode(["status" => "success", "data" => $data]);


?>
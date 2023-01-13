<?php
require "../../config.php";
require "../base/db.php";
require "../base/mail.php";
session_start();



if(!empty($_GET['email']) and !empty($_GET['ktp_nama']) and !empty($_GET['nik_ktp']) and !empty($_GET['password']))
{
	$email = $_GET['email'];
	$ktp_nama = $_GET['ktp_nama'];
	$nik_ktp = $_GET['nik_ktp'];
	$password = $hasher->HashPassword($_GET['password']);

	$hashUrl = $dir . "lib/ods/verification.php?token=" . md5($email . "-". $nik_ktp);

	$date = date("Y-m-d H:i:s");

	$qCekNIK = $adeQ->select($adeQ->prepare("select count(1) as cek from core_user
	where userid=%s", $nik_ktp));

	// $qCekNIKReg = $adeQ->select($adeQ->prepare("select count(1) as cek from reg_online.data_trx_registrasi_online
	// where nik_ktp=%s and validasi_email is true", $nik_ktp));

	if($qCekNIK[0]['cek'] == 0){
		// if($qCekNIKReg[0]['cek'] == 0){
			if(strlen($nik_ktp) == 16){

				$massage = "
				Halo $ktp_nama , <br>
				Terima kasih sudah melakukan registrasi online di UMKM Kementrian Koperasi, Silakan verifikasi akun anda agar dapat
				masuk ke halaman ODS UMKM : <br><br>
				$hashUrl
				<br><br>
				Silakan di akses dan di lengkapi.
				<br><br>
				Terima Kasih
				<br>
				Kementrian Koperasi
				";

				if(send_email($email, $ktp_nama, "Registrasi ODS UMKM", $massage)){
					$ins = $adeQ->query("
						insert into core_user
						(email, userid, userpass, username, isactive, idgroup, typegroup, createby, createdate) values
						('$email', '$nik_ktp', '$password' ,'$ktp_nama', 0, 4, 'UMKM', 'register_online', now())
					");
					if($ins){
						echo json_encode(['status'=>'success', 'text'=>'Registrasi Berhasil <br> Link Registrasi akan di kirim via email']);
					}
				}else{
					echo json_encode(['status'=>'error', 'text'=>'Email tidak dapat di kirim']);	
				}
				
			}else{
				echo json_encode(['status'=>'error', 'text'=>'NIK tidak sesuai format']);	
			}
		// }else{
		// 	echo json_encode(['status'=>'error', 'text'=>'NIK Anda sudah pernah di daftarkan']);
		// }
	}else{
		echo json_encode(['status'=>'error', 'text'=>'NIK Anda sudah terdaftar di UMKM']);
	}


		

	
}else{
	echo json_encode(['status'=>'error', 'text'=>'Mohon lengkapi semua data']);
}
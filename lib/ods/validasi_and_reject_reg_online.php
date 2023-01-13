<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "functions.php";


if(isset($_SESSION['userid']))
{
	if(isset($_POST['id']) and isset($_POST['type']))
  	{

		$id = $_POST['id'];
		$type = $_POST['type'];
		$date = date("Y-m-d H:i:s");
		

		

		switch($type){
			case "vervikasi" :

				$status = "success";
				$msg = "Data Berhasil Di Verfikasi !!";
		
				$getKtpPemilikUsaha = $adeQ->select($adeQ->prepare(
					"select * from reg_online.data_mst_pemilik_usaha_registrasi_online
					 where id_registrasi_online = %d
					", $id
				));
		
				$qUpdPemilikUsaha = $adeQ->query($adeQ->prepare(
					"update reg_online.data_mst_pemilik_usaha_registrasi_online
					set 
					id_user_verifikasi = %d, 
					verifikasi_date = %s
					where id_registrasi_online = %d", $_SESSION['userUniqId'], $date, $id
				));
		
				if($qUpdPemilikUsaha){
					$qUpdUsaha = $adeQ->query($adeQ->prepare(
						"update reg_online.data_mst_usaha_registrasi_online
						set 
						id_user_verifikasi = %d, 
						verifikasi_date = %s
						where id_registrasi_online = %d", $_SESSION['userUniqId'], $date, $id
					));
		
					if($qUpdUsaha){
						$qInsPemilikUsaha = $adeQ->query($adeQ->prepare(
							"INSERT INTO data_mst_pemilik_usaha 
							 (nik_ktp, ktp_nama, id_kabupaten_kota_ktp_tempat_lahir, ktp_tgl_lahir, ktp_jenis_kelamin, id_kelurahan_ktp, id_kecamatan_ktp, ktp_alamat, ktp_rt, ktp_rw, id_ktp_agama, ktp_pekerjaan, golongan_darah, tlp_mobile, email, id_provinsi_domisili, id_kabupaten_kota_domisili, id_kecamatan_domisili, id_kelurahan_domisili, domisili_kodepos, domisili_alamat, domisili_rt, domisili_rw, insert_date, update_date, id_user_insert, id_user_update, id_user_verifikasi, verifikasi_date)
							 select nik_ktp, ktp_nama, id_kabupaten_kota_ktp_tempat_lahir, ktp_tgl_lahir, ktp_jenis_kelamin, id_kelurahan_ktp, id_kecamatan_ktp, ktp_alamat, ktp_rt, ktp_rw, id_ktp_agama, ktp_pekerjaan, golongan_darah, tlp_mobile, email, id_provinsi_domisili, id_kabupaten_kota_domisili, id_kecamatan_domisili, id_kelurahan_domisili, domisili_kodepos, domisili_alamat, domisili_rt, domisili_rw, insert_date, update_date, id_user_insert, id_user_update, id_user_verifikasi, verifikasi_date
							 from reg_online.data_mst_pemilik_usaha_registrasi_online where id_registrasi_online=%d
							 ", $id
						));
		
						if($qInsPemilikUsaha){
		
							$getIdPemilikUsaha = $adeQ->select($adeQ->prepare(
								"select * from data_mst_pemilik_usaha
								 where nik_ktp = %s and id_user_verifikasi = %d
								", $getKtpPemilikUsaha[0]['nik_ktp'], $_SESSION['userUniqId']
							));
		
							$qInsUsaha = $adeQ->query($adeQ->prepare(
								"INSERT INTO data_mst_usaha(id_pemilik_usaha, id_bentuk_usaha, nama_usaha, nama_izin_usaha, no_izin_usaha, tgl_penerbit_izin_usaha, tgl_mulai_usaha, npwp_usaha, id_provinsi, id_kabupaten_kota, id_kecamatan, id_kelurahan, kodepos, alamat_usaha, rt_usaha, rw_usaha, tlp_kantor, tlp_mobile, fax, email_usaha, website, media_sosial, nama_akun_media_sosial, nama_brand, catatan, id_user_insert, tanggal_pendataan, insert_date, update_date, id_user_update, nama_sumber_pendata, id_jenis_usaha, id_kbli, nib, id_user_verifikasi, verifikasi_date) 
								select ".$getIdPemilikUsaha[0]['id']." as id_pemilik_usaha, id_bentuk_usaha, nama_usaha, nama_izin_usaha, no_izin_usaha, tgl_penerbit_izin_usaha, tgl_mulai_usaha, npwp_usaha, id_provinsi, id_kabupaten_kota, id_kecamatan, id_kelurahan, kodepos, alamat_usaha, rt_usaha, rw_usaha, tlp_kantor, tlp_mobile, fax, email_usaha, website, media_sosial, nama_akun_media_sosial, nama_brand, catatan, id_user_insert, tanggal_pendataan, insert_date, update_date, id_user_update, nama_sumber_pendata, id_jenis_usaha, id_kbli, nib, id_user_verifikasi, verifikasi_date
								from reg_online.data_mst_usaha_registrasi_online where id_registrasi_online=%d
								 ", $id
							));
		
							if($qInsUsaha){
								$getIdUsaha = $adeQ->select($adeQ->prepare(
									"select * from data_mst_usaha
									 where id_pemilik_usaha = %d and id_user_verifikasi = %d
									", $getIdPemilikUsaha[0]['id'], $_SESSION['userUniqId']
								));
		
								$qInsValidasi = $adeQ->query(
									"INSERT INTO data_validasi_registrasi_online(id_registrasi_online, id_pemilik_usaha, id_usaha, flag_validasi, flag_verifikasi, id_user_verifikasi, verifikasi_date, id_user_insert, insert_date) 
									VALUES ($id, ".$getIdPemilikUsaha[0]['id'].", ".$getIdUsaha[0]['id'].", true, true, ".$_SESSION['userUniqId'].", '$date', ".$_SESSION['userUniqId'].", '$date')
									"
								);

								if(!$qInsValidasi){
									$status = 'error';
									$msg = 'Error Insert data_validasi_registrasi_online';
								}

							}else{
								$status = 'error';
								$msg = 'Error Insert data_mst_usaha';
							}
						}else{
							$status = 'error';
							$msg = 'Error Insert data_mst_pemilik_usaha';
						}
					}else{
						$status = 'error';
						$msg = 'Error Update data_mst_usaha_registrasi_online';
					}
				}else{
					$status = 'error';
					$msg = 'Error Update data_mst_pemilik_usaha_registrasi_online';
				}

				echo json_encode(["status" => $status, "msg" => $msg]);

			break;

			case "reject" :
				$status = "success";
				$msg = "Data Berhasil Di Reject !!";
		
				$getIdPemilikUsahaOnline = $adeQ->select($adeQ->prepare(
					"select id from reg_online.data_mst_pemilik_usaha_registrasi_online
					 where id_registrasi_online = %d
					", $id
				));

				$getIdUsahaOnline = $adeQ->select($adeQ->prepare(
					"select id from reg_online.data_mst_usaha_registrasi_online
					 where id_registrasi_online = %d
					", $id
				));

				$qUpdPemilikUsaha = $adeQ->query($adeQ->prepare(
					"update reg_online.data_mst_pemilik_usaha_registrasi_online
					set 
					id_user_verifikasi = %d, 
					verifikasi_date = %s
					where id_registrasi_online = %d", $_SESSION['userUniqId'], $date, $id
				));
		
				if($qUpdPemilikUsaha){
					$qUpdUsaha = $adeQ->query($adeQ->prepare(
						"update reg_online.data_mst_usaha_registrasi_online
						set 
						id_user_verifikasi = %d, 
						verifikasi_date = %s
						where id_registrasi_online = %d", $_SESSION['userUniqId'], $date, $id
					));

					if($qUpdUsaha){
						$qInsValidasi = $adeQ->query(
							"INSERT INTO data_validasi_registrasi_online(id_registrasi_online, id_pemilik_usaha, id_usaha, flag_validasi, flag_verifikasi, id_user_verifikasi, verifikasi_date, id_user_insert, insert_date) 
							VALUES ($id, ".$getIdPemilikUsahaOnline[0]['id'].", ".$getIdUsahaOnline[0]['id'].", false, false, ".$_SESSION['userUniqId'].", '$date', ".$_SESSION['userUniqId'].", '$date')
							"
						);

						if(!$qInsValidasi){
							$status = 'error';
							$msg = 'Error Insert data_validasi_registrasi_online';
						}
					}else{
						$status = 'error';
						$msg = 'Error Update data_mst_usaha_registrasi_online';
					}
				}else{
					$status = 'error';
					$msg = 'Error Update data_mst_pemilik_usaha_registrasi_online';
				}

				echo json_encode(["status" => $status, "msg" => $msg]);

				
			break;
		}

		
		
	} // close $f
}// close session

?>
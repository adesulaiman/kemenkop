<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "functions.php";


if(isset($_SESSION['userid']))
{
	if(isset($_POST['f']) and isset($_POST['formType']))
  	{
		$f = $_POST['f'];
		$type = $_POST['formType'];

		//validasi untuk bantuan usaha, no tlp pada pemilik usaha harus di isi
		if($f == 39){
			if($type != 'delete'){
				$idPmilikUsaha = $_POST['id_pemilik_usaha'];
				$q = $adeQ->select($adeQ->prepare("select * from check_tlp_mobile_pemilik_usaha where filter=%d", $idPmilikUsaha));
				if($q[0]['text'] == ''){
					echo json_encode(['status'=>false, 'info'=>'No Telp Mobile pada data pemilik usaha masih koosong']);
				}else{
					echo json_encode(['status'=>true, 'info'=>'']);
				}
			}else{
				echo json_encode(['status'=>true, 'info'=>'']);
			}
			
		}

		if($f == 53){
			if($type != 'delete'){
				$idPmilikUsaha = $_POST['id_pemilik_usaha'];
				$qidKeuangan = $adeQ->select($adeQ->prepare("select count(1) as cek from data_trx_indikator_keuangan where id_pemilik_usaha=%d", $idPmilikUsaha));
				$qidKelembang = $adeQ->select($adeQ->prepare("select count(1) as cek from data_trx_indikator_kelembagaan_karyawan where id_pemilik_usaha=%d", $idPmilikUsaha));
				$qidTkerja = $adeQ->select($adeQ->prepare("select count(1) as cek from data_trx_indikator_kelembagaan_tenagakerja where id_pemilik_usaha=%d", $idPmilikUsaha));
				
				$status = true;
				$errInfo = '';

				if($qidKeuangan[0]['cek'] == 0){
					$status = false;
					$errInfo .= '* Data indikator keuangan wajid di ini jika ingin mengisi form ini !! <br>';
				}
				
				if($qidKelembang[0]['cek'] == 0){
					$status = false;
					$errInfo .= '* Data indikator kelembagaan karyawan wajid di ini jika ingin mengisi form ini !! <br>';
				}
				
				if($qidTkerja[0]['cek'] == 0){
					$status = false;
					$errInfo .= '* Data indikator kelembagaan tenaga kerja wajid di ini jika ingin mengisi form ini !! <br>';
				}

				echo json_encode(['status'=>$status, 'info'=>$errInfo]);

			}else{
				echo json_encode(['status'=>true, 'info'=>'']);
			}
			
		}

	} // close $f
}// close session

?>
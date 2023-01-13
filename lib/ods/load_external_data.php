<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if(isset($_SESSION['userid']))
{
	if(isset($_GET['tipe']))
  	{
		  
		$f = $_GET['tipe'];

		switch($f){
			case "getDetailProvKel" : 

			$idKel = $_GET['id_kel'];

			$getData = $adeQ->select($adeQ->prepare(
				"select 
				k.id as id_kel,
				k.kode_pos,
				k.nama_kelurahan,
				k.id_kecamatan,
				kc.nama_kecamatan,
				k.id_kabupaten_kota,
				kb.nama_kabupaten_kota,
				k.id_provinsi,
				p.nama_provinsi
				from data_ref_kelurahan k
				inner join data_ref_kabupaten_kota kb on k.id_kabupaten_kota=kb.id
				inner join data_ref_kecamatan kc on k.id_kecamatan = kc.id
				inner join data_ref_provinsi p on k.id_provinsi=p.id 
				where k.id=%d", $idKel
			));
			
			## Response
			$response = array(
				"data" => $getData
			);
	
			echo json_encode($response);

			break;
		}


		
	}//close $f $t
}// close session

?>
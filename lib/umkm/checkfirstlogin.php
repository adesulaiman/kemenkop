<?php

if($_SESSION['typegroup'] == 'UMKM'){
	$cekNIK = $adeQ->select("select count(1) cek from (
        select nik_ktp from data_mst_pemilik_usaha
            union all
            select nik_ktp from data_mst_pemilik_usaha_stg
	) as ktp where nik_ktp='$_SESSION[userid]'");
	
	if($cekNIK[0]['cek'] == 0){
		header("Location: ".$dir."welcome_umkm.php");
		exit;
	}
}


?>
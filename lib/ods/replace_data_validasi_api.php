<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "functions.php";


if(isset($_SESSION['userid']))
{
	if(isset($_POST['id']) and isset($_POST['f']))
  	{

		$id = $_POST['id'];
		$f = $_POST['f'];
		$status = "success";
		$msg = "Data Berhasil Di Ubah !!";

		$qField = $adeQ->select($adeQ->prepare(
		    "select * from core_rest_api ap
			inner join core_fields f on ap.id_core_field=f.id
			where f.id_form=%d and key<>'pk'", $f
		));

		$qForm = $adeQ->select($adeQ->prepare(
		    "select * from core_forms
			where idform = %d", $f
		));

		$dataUpd = array();
		$errorMsg = array();
		foreach($qField as $field){
			if($field['type_input'] == 'select'){
				if($field['name_field'] == 'ktp_jenis_kelamin'){
					$dataUpd[] = $field['name_field'] . " = '" . $_POST['api'.$field['name_field'].'-'.$field['id_form']] ."'";
				}else{
					preg_match('/(?<=t=).*/',$field['link_type_input'], $dbSelect);
					$dbS = str_replace("&filter=all", "", $dbSelect[0]);
					$vSelectBox = $adeQ->select("select * from $dbS where lower(text) = '". strtolower($_POST['api'.$field['name_field'].'-'.$field['id_form']])."'");
					if(count($vSelectBox) == 1){
						$dataUpd[] = $field['name_field'] . " = " . $vSelectBox[0]['id'];
					}else{
						$status = "error";
						$errorMsg[] = "Data $field[name_field] tidak ada di table referensi";
					}
				}
				

			}else if($field['type_input'] == 'text' or $field['type_input'] == 'date'){
				$dataUpd[] = $field['name_field'] . " = '" . $_POST['api'.$field['name_field'].'-'.$field['id_form']] ."'";
			}
		}

		$dataUpdJoin = implode(", ", $dataUpd);
		if($status == 'error'){
			$msg = implode("<br>", $errorMsg);
		}else{
			$qUpd = $adeQ->query($adeQ->prepare("
				update ".$qForm[0]['formcode']." set
				$dataUpdJoin
				where id_registrasi_online = %d", $id
			));

			if(!$qUpd){
				$status = 'error';
				$msg = 'Errpr update data';
			}
		}		
		

		echo json_encode(["status" => $status, "msg" => $msg]);
		
		
	} // close $f
}// close session

?>
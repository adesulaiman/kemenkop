<?php
require "../../config.php";
require "security_register.php";
require "../base/db.php";
require "functions.php";


if(isset($_SESSION['idRegister']))
{
	if(isset($_POST['f']))
  	{

		$f = $_POST['f'];
		

		$qForm = $adeQ->select(
		    "select * from core_forms where idform in ($f) order by idform "
		);

		$stt = true;
		$validate = array();
		$msg = '';
		$id_pemilik_usaha = null;

		foreach($qForm as $valForm)
		{
		  //CEK VALIDATE
			$qField = $adeQ->select($adeQ->prepare(
				"select * from core_fields where id_form=%d and active is true order by id", $valForm['idform']
			));

			foreach($qField as $field)
			{
				if($field['type_field'] == 'nm')
					{
						$data = isset($_POST[$field['name_field'].'-'.$field['id_form']]) ? $_POST[$field['name_field'].'-'.$field['id_form']] : null;


						if($field['validate'] == 't')
						{
							$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];

							if(empty($data))
							{
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'].'-'.$field['id_form'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}else{
								$validate[] = array(
									'field' => $field['name_field'].'-'.$field['id_form'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if($field['type_input'] != 'checkbox')
							{
								if(strlen($data) != $validate_length)
								{
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'].'-'.$field['id_form'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}
						}
					}
			
			}

		}

		
		if($stt)
		{
			//CEK REGISTER KTP
			$qCekKTP = $adeQ->select($adeQ->prepare(
				"select count(1) as cek from reg_online.data_mst_pemilik_usaha_registrasi_online where nik_ktp=%s",
				$_SESSION['nik_ktp']
			));

			if($qCekKTP[0]['cek'] == 0){

				foreach($qForm as $valForm)
				{
					$formName = $valForm['formname'];
					$formDesc = $valForm['description'];
					$formCode = $valForm['formcode'];
					

					if($valForm['idform'] == 47){	

						$ins = array();
						$fieldNm = array();

						$qField = $adeQ->select($adeQ->prepare(
							"select * from core_fields where id_form=%d and active is true order by id", $valForm['idform']
						));

						foreach($qField as $field)
						{
							if($field['type_field'] == 'nm')
							{
								if($field['name_field'] == 'nik_ktp'){
									$data = $_SESSION['nik_ktp'];
								}else{
									$data = isset($_POST[$field['name_field'].'-'.$field['id_form']]) ? $_POST[$field['name_field'].'-'.$field['id_form']] : null;
								}
								

								if($field['type_input'] == 'number')
								{
									$ins[] = $data;
								}elseif($field['type_input'] == 'date')
								{
									$date = date('Y-m-d', strtotime($data));
									$ins[] = "'$date'";
								}elseif($field['type_input'] == 'checkbox')
								{
									$datarr = array();
									if($data != null)
									{
										foreach ($data as $key => $value) {
											$datarr[] = $value;
										} 
									}
									$ins[] = "'".implode("|", $datarr)."'";	
								}else
								{
									$ins[] = "'$data'";
								}
								$fieldNm[] = $field['name_field'];

							}else if($field['type_field'] == 'sys_ins_usr')
							{

								$ins[] = 9999;//$_SESSION['userUniqId'];
								$fieldNm[] = $field['name_field'];

							}else if($field['type_field'] == 'sys_ins_time')
							{

								$ins[] = "'".date("Y-m-d H:i:s")."'";
								$fieldNm[] = $field['name_field'];
							}else if($field['type_field'] == 'sys_ins_usr_online')
							{

								$ins[] = $_SESSION['idRegister'];
								$fieldNm[] = $field['name_field'];
							}
						}

						$q = "insert into $formCode (".implode(",", $fieldNm).") values (".implode(",", $ins).")";

						$ins = $adeQ->query($q);
						$msg = 'Data Berhasil Di Simpan';
						if(!$ins)
						{
							$msg = 'Error Insert Data';
							$stt = false;
						}else{
							//GET ID PEMILIK USAHA ONLINE
							$qGetIdPemilikUsaha = $adeQ->select($adeQ->prepare(
								"select id from reg_online.data_mst_pemilik_usaha_registrasi_online where nik_ktp=%s",
								$_SESSION['nik_ktp']
							));
							$id_pemilik_usaha = $qGetIdPemilikUsaha[0]['id'];
						}

					}else{
						
						$ins = array();
						$fieldNm = array();

						//add field and value id_pemilik_usaha
						$ins[] = $id_pemilik_usaha;
						$fieldNm[] = "id_pemilik_usaha";

						$qField = $adeQ->select($adeQ->prepare(
							"select * from core_fields where id_form=%d and active is true order by id", $valForm['idform']
						));

						foreach($qField as $field)
						{
							if($field['type_field'] == 'nm')
							{
								$data = isset($_POST[$field['name_field'].'-'.$field['id_form']]) ? $_POST[$field['name_field'].'-'.$field['id_form']] : null;

								if($field['type_input'] == 'number')
								{
									$ins[] = $data;
								}elseif($field['type_input'] == 'date')
								{
									$date = date('Y-m-d', strtotime($data));
									$ins[] = "'$date'";
								}elseif($field['type_input'] == 'checkbox')
								{
									$datarr = array();
									if($data != null)
									{
										foreach ($data as $key => $value) {
											$datarr[] = $value;
										} 
									}
									$ins[] = "'".implode("|", $datarr)."'";	
								}else
								{
									$ins[] = "'$data'";
								}
								$fieldNm[] = $field['name_field'];

							}else if($field['type_field'] == 'sys_ins_usr')
							{

								$ins[] = 9999;//$_SESSION['userUniqId'];
								$fieldNm[] = $field['name_field'];

							}else if($field['type_field'] == 'sys_ins_time')
							{

								$ins[] = "'".date("Y-m-d H:i:s")."'";
								$fieldNm[] = $field['name_field'];
							}else if($field['type_field'] == 'sys_ins_usr_online')
							{

								$ins[] = $_SESSION['idRegister'];
								$fieldNm[] = $field['name_field'];
							}
						}

						$q = "insert into $formCode (".implode(",", $fieldNm).") values (".implode(",", $ins).")";

						$ins = $adeQ->query($q);
						$msg = '<h3>Registrasi Berhasil <br><br> Data anda akan kami verifikasi dan validasi <br><br> Terima Kasih</h3>';
						if(!$ins)
						{
							$msg = 'Error Insert Data';
							$stt = false;
						}

					}
					

				}
				
			}else{
				$msg = 'NIK KTP Sudah Terdaftar';
				$stt = false;
			}

		}else{
			$msg = 'Mohon Lengkapi Data Terlebih Dahulu';
		}

		echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);

		
	} // close $f
}// close session

?>
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
		$formType = $_POST['formType'];
		$qField = $adeQ->select($adeQ->prepare(
		    "select * from core_fields where id_form=%d and active is true order by id", $f
		));

		$qForm = $adeQ->select($adeQ->prepare(
		    "select * from core_forms where idform=%d", $f
		));

		foreach($qForm as $valForm)
		{
		  $formName = $valForm['formname'];
		  $formDesc = $valForm['description'];
		  $formCode = $valForm['formcode'];
		}

		$stt = true;
		$validate = array();
		$bypass = array();
		$ins = array();
		$fieldNm = array();
		$msg = '';

		switch ($formType) {
			case 'add':
				foreach($qField as $field)
				{
					if($field['type_field'] == 'nm')
					{
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						//SET COOKIES IF CONFIG TRUE
						if($field['cookies_action'] == 'create'){
							$_SESSION[$field['name_field']] = $data;
						}


						if($field['validate'] == 't')
						{
							$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];

							if(empty($data))
							{
								//CONDITION JIKA BENTUK USAHA TIDAK BERBADAN HUKUM ATAU PERORANGAN MAKA NPWP TIDAK MANDATORY
								if($field['name_field'] == 'npwp_usaha'){
									if($_POST['id_bentuk_usaha'] == 8){
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => '',
											'msg' => $field['msg_validate']
										);
									}else{
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => $field['msg_validate']
										);
									}
								}else{
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
								
							}else{
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if($field['type_input'] != 'checkbox')
							{
								if(strlen($data) != $validate_length)
								{
									//CONDITION JIKA BENTUK USAHA TIDAK BERBADAN HUKUM ATAU PERORANGAN MAKA NPWP TIDAK MANDATORY
									if($field['name_field'] == 'npwp_usaha'){
										if($_POST['id_bentuk_usaha'] == 8){
											$validate[] = array(
												'field' => $field['name_field'],
												'err' => '',
												'msg' => $field['msg_validate']
											);
										}else{
											$stt = false;
											$validate[] = array(
												'field' => $field['name_field'],
												'err' => 'validate',
												'msg' => $field['msg_validate']
											);
										}
									}else{
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => $field['msg_validate']
										);
									}
								}
							}
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
							$ins[] = "'".str_replace("'","''", $data)."'";
						}
						$fieldNm[] = $field['name_field'];

					}else if($field['type_field'] == 'sys_ins_usr')
					{

						$ins[] = $_SESSION['userUniqId'];
						$fieldNm[] = $field['name_field'];

					}else if($field['type_field'] == 'sys_ins_time')
					{

						$ins[] = "'".date("Y-m-d H:i:s")."'";
						$fieldNm[] = $field['name_field'];
					}
				}

				if($stt)
				{
					$q = "insert into $formCode (".implode(",", $fieldNm).") values (".implode(",", $ins).")";
					$ins = $adeQ->query($q);
					$msg = 'Data Berhasil Di Simpan';
					if(!$ins)
					{
						$msg = 'Error Insert Data';
						$stt = false;
					}
				}

				

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);
				break;
			
			case 'edit' :

				$upd = array();
				$dtUpd = null;

				foreach($qField as $field)
				{
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

					//SET COOKIES IF CONFIG TRUE
					if($field['cookies_action'] == 'create'){
						$_SESSION[$field['name_field']] = $data;
					}
					
					if($field['type_field'] == 'nm')
					{
						if($field['validate'] == 't')
						{

							$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];
							
							if(empty($data))
							{

								//CONDITION JIKA BENTUK USAHA TIDAK BERBADAN HUKUM ATAU PERORANGAN MAKA NPWP TIDAK MANDATORY
								if($field['name_field'] == 'npwp_usaha'){
									if($_POST['id_bentuk_usaha'] == 8){
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => '',
											'msg' => $field['msg_validate']
										);
									}else{
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => $field['msg_validate']
										);
									}
								}else{
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}else{
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if($field['type_input'] != 'checkbox')
							{
								if(strlen($data) != $validate_length)
								{
									//CONDITION JIKA BENTUK USAHA TIDAK BERBADAN HUKUM ATAU PERORANGAN MAKA NPWP TIDAK MANDATORY
									if($field['name_field'] == 'npwp_usaha'){
										if($_POST['id_bentuk_usaha'] == 8){
											$validate[] = array(
												'field' => $field['name_field'],
												'err' => '',
												'msg' => $field['msg_validate']
											);
										}else{
											$stt = false;
											$validate[] = array(
												'field' => $field['name_field'],
												'err' => 'validate',
												'msg' => $field['msg_validate']
											);
										}
									}else{
										$stt = false;
										$validate[] = array(
											'field' => $field['name_field'],
											'err' => 'validate',
											'msg' => $field['msg_validate']
										);
									}
								}
							}
						}

						if($field['type_input'] == 'number')
						{
							$dtUpd = $data;
						}elseif($field['type_input'] == 'date')
						{
							$date = date("Y-m-d", strtotime($data));
							$dtUpd = "'$date'";
						}elseif($field['type_input'] == 'checkbox')
						{
							$datarr = array();
							if($data != null)
							{
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								} 
							}
							$dtUpd = "'".implode("|", $datarr)."'";	
						}else
						{
							$dtUpd = "'".str_replace("'","''", $data)."'";
						}
						
						$upd[] = $field['name_field'] . "= $dtUpd";
					}
					else if ($field['type_field'] == 'pk')
					{
						$where = $field['name_field'] . "= $data";
					}
					else if($field['type_field'] == 'sys_upd_usr')
					{

						$dtUpd = $_SESSION['userUniqId'];
						$upd[] = $field['name_field'] . "= $dtUpd";

					}
					else if($field['type_field'] == 'sys_upd_time')
					{

						$dtUpd = "'".date("Y-m-d H:i:s")."'";
						$upd[] = $field['name_field'] . "= $dtUpd";
					}
				}

				if($stt)
				{
					$q = "update $formCode set ".implode(",", $upd)." where $where";
					$update = $adeQ->query($q);
					$msg = 'Data Berhasil Di Perbarui';
					if(!$update)
					{
						$msg = 'Error Edit Data';
						$stt = false;
					}
				}

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);
				break;

			case 'delete' :
				foreach($qField as $field)
				{
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;
					
					if($field['type_field'] == 'pk')
					{
						$where = $field['name_field'] . "= $data";
					}
				}

				if($stt)
				{
					$q = "delete from $formCode where $where";
					$del = $adeQ->query($q);
					$msg = 'Data Berhasil Di Hapus';
					if(!$del)
					{
						$msg = 'Error Delete Data';
						$stt = false;
					}
				}

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);

				break;
		}
	} // close $f
}// close session

?>
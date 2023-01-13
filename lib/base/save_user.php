<?php
require "../../config.php";
require "security_login.php";
require "db.php";


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
					if($field['type_field'] != 'pk')
					{
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						if($field['validate'] == 't')
						{
							if(empty($data))
							{
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}else{
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}
						}

						if($field['type_input'] == 'number')
						{
							$ins[] = $data;
						}elseif($field['type_input'] == 'date')
						{
							$date = date($datePHP, strtotime($data));
							$ins[] = "'$date'";
						}elseif($field['type_input'] == 'password')
						{
							$pass = $hasher->HashPassword($data);
							$ins[] = "'$pass'";
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
					}
				}

				if($stt)
				{
					$q = "insert into $formCode (".implode(",", $fieldNm).") values (".implode(",", $ins).")";
                                        error_log($q);
					$ins = $adeQ->query($q);
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
					
					if($field['type_field'] != 'pk')
					{
						if($field['validate'] == 't')
						{
							if(empty($data))
							{
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							}else{
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}
						}

						if($field['type_input'] == 'number')
						{
							$dtUpd = $data;
						}elseif($field['type_input'] == 'date')
						{
							$date = date($datePHP, strtotime($data));
							$dtUpd = "'$date'";
						}elseif($field['type_input'] == 'password')
						{
							$cekCurrPass = $adeQ->select("select * from $formCode where id=$_POST[id]");
							if($cekCurrPass[0]['userpass'] != $data)
							{
								$pass = $hasher->HashPassword($data);
								$dtUpd = "'$pass'";	
							}else
							{
								$dtUpd = "'$data'";
							}
							
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
							$dtUpd = "'$data'";
						}
						
						$upd[] = $field['name_field'] . "= $dtUpd";
					}else
					{
						$where = $field['name_field'] . "= $data";
					}
				}

				if($stt)
				{
					$q = "update $formCode set ".implode(",", $upd)." where $where";
					$update = $adeQ->query($q);
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
					if(!$del)
					{
						$msg = 'Error Delete Data';
						$stt = false;
					}
				}

				echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);

				break;
		}
	} //close $f
}// close session

?>

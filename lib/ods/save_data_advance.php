<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "functions.php";


if (isset($_SESSION['userid'])) {
	if (isset($_POST['f']) and isset($_POST['formType'])) {

		$f = $_POST['f'];
		$formType = $_POST['formType'];
		$qField = $adeQ->select($adeQ->prepare(
			"select * from core_fields where id_form=%d and active is true order by id",
			$f
		));

		$qForm = $adeQ->select($adeQ->prepare(
			"select * from core_forms where idform=%d",
			$f
		));

		foreach ($qForm as $valForm) {
			$formName = $valForm['formname'];
			$formDesc = $valForm['description'];
			$formCode = $valForm['formcode'];
			$stgtable = $valForm['stgtable'];
		}

		$stt = true;
		$validate = array();
		$bypass = array();
		$ins = array();
		$fieldNm = array();
		$msg = '';

		switch ($formType) {
			case 'add':
				foreach ($qField as $field) {
					if ($field['type_field'] == 'nm') {
						$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

						//SET COOKIES IF CONFIG TRUE
						if ($field['cookies_action'] == 'create') {
							$_SESSION[$field['name_field']] = $data;
						}

						if ($field['validate'] == 't') {
							$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];

							if (empty($data)) {
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							} else {
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if ($field['type_input'] != 'checkbox') {
								if (strlen($data) != $validate_length) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}
						}


						if ($field['type_input'] == 'number') {
							$ins[] = $data;
						} elseif ($field['type_input'] == 'date') {
							$date = date('Y-m-d', strtotime($data));
							$ins[] = "'$date'";
						} elseif ($field['type_input'] == 'checkbox') {
							$datarr = array();
							if ($data != null) {
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								}
							}
							$ins[] = "'" . implode("|", $datarr) . "'";
						} else {
							$ins[] = "'" . str_replace("'", "''", $data) . "'";
						}
						$fieldNm[] = $field['name_field'];
					} else if ($field['type_field'] == 'sys_ins_usr') {

						$ins[] = $_SESSION['userUniqId'];
						$fieldNm[] = $field['name_field'];
					} else if ($field['type_field'] == 'sys_ins_time') {

						$ins[] = "'" . date("Y-m-d H:i:s") . "'";
						$fieldNm[] = $field['name_field'];
					}
				}

				if ($stt) {
					$q = "insert into $formCode (" . implode(",", $fieldNm) . ") values (" . implode(",", $ins) . ")";
					$ins = $adeQ->query($q);
					$msg = 'Data Berhasil Di Simpan';
					if (!$ins) {
						$msg = 'Error Insert Data';
						$stt = false;
					}
				}



				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);
				break;

			case 'edit':

				$upd = array();
				$dtUpd = null;

				foreach ($qField as $field) {
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

					//SET COOKIES IF CONFIG TRUE
					if ($field['cookies_action'] == 'create') {
						$_SESSION[$field['name_field']] = $data;
					}

					if ($field['type_field'] == 'nm') {
						if ($field['validate'] == 't') {

							$validate_length = ($field['validate_length'] == null) ? strlen($data) : $field['validate_length'];

							if (empty($data)) {
								$stt = false;
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => 'validate',
									'msg' => $field['msg_validate']
								);
							} else {
								$validate[] = array(
									'field' => $field['name_field'],
									'err' => '',
									'msg' => $field['msg_validate']
								);
							}

							if ($field['type_input'] != 'checkbox') {
								if (strlen($data) != $validate_length) {
									$stt = false;
									$validate[] = array(
										'field' => $field['name_field'],
										'err' => 'validate',
										'msg' => $field['msg_validate']
									);
								}
							}
						}

						if ($field['type_input'] == 'number') {
							$dtUpd = $data;
						} elseif ($field['type_input'] == 'date') {
							$date = date("Y-m-d", strtotime($data));
							$dtUpd = "'$date'";
						} elseif ($field['type_input'] == 'checkbox') {
							$datarr = array();
							if ($data != null) {
								foreach ($data as $key => $value) {
									$datarr[] = $value;
								}
							}
							$dtUpd = "'" . implode("|", $datarr) . "'";
						} else {
							$dtUpd = "'" . str_replace("'", "''", $data) . "'";
						}

						$upd[] = $field['name_field'] . "= $dtUpd";
					} else if ($field['type_field'] == 'pk') {
						$where = $field['name_field'] . "= $data";
					} else if ($field['type_field'] == 'sys_upd_usr') {

						$dtUpd = $_SESSION['userUniqId'];
						$upd[] = $field['name_field'] . "= $dtUpd";
					} else if ($field['type_field'] == 'sys_upd_time') {

						$dtUpd = "'" . date("Y-m-d H:i:s") . "'";
						$upd[] = $field['name_field'] . "= $dtUpd";
					}
				}

				if ($stt) {
					$q = "update $formCode set " . implode(",", $upd) . " where $where";
					$update = $adeQ->query($q);
					$msg = 'Data Berhasil Di Perbarui';
					if (!$update) {
						$msg = 'Error Edit Data';
						$stt = false;
					}
				}

				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);
				break;

			case 'delete':
				foreach ($qField as $field) {
					$data = isset($_POST[$field['name_field']]) ? $_POST[$field['name_field']] : null;

					if ($field['type_field'] == 'pk') {
						$where = $field['name_field'] . "= $data";
					}
				}

				if ($stt) {
					$q = "delete from $formCode where $where";
					$del = $adeQ->query($q);
					$msg = 'Data Berhasil Di Hapus';
					if (!$del) {
						$msg = 'Error Delete Data';
						$stt = false;
					}
				}

				echo json_encode(['status' => $stt, 'validate' => $validate, 'msg' => $msg]);

				break;


			case 'approve_pem_usaha':
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];
					$fieldData = array();
					$fieldDataStg = array();
					foreach ($qField as $field) {
						$fieldData[] = $field['name_field'];
						if ($field['name_field'] == 'id') {
							$fieldDataStg[] = "nik_ktp::bigint as id";
						} else {
							$fieldDataStg[] = $field['name_field'];
						}
					}

					$SQLDel = "delete from $stgtable where nik_ktp in ($id)";
					$SQLDel2 = "delete from $formCode where nik_ktp in ($id)";
					$SQLIns = "insert into $stgtable (" . implode(",", $fieldData) . ", id_user_verifikasi, verifikasi_date) select " . implode(",", $fieldDataStg) . ", $_SESSION[userUniqId], now() from $formCode where nik_ktp in ($id)";
					$quer = $adeQ->query($SQLDel);
					$quer2 = $adeQ->query($SQLIns);

					if ($quer2) {
						$quer3 = $adeQ->query($SQLDel2);
						echo json_encode(['status' => "success", 'msg' => "Approvel successfully !!"]);
					} else {
						echo json_encode(['status' => "error", 'msg' => "Error approvel data !!"]);
					}
				} else {
					echo json_encode(['status' => "error", 'msg' => "Please select data for approval !!"]);
				}
				break;

			case 'reject_pem_usaha':
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];

					$SQLDel2 = "delete from $formCode where nik_ktp in ($id)";
					$quer = $adeQ->query($SQLDel2);

					if ($SQLDel2) {
						echo json_encode(['status' => "success", 'msg' => "Reject successfully !!"]);
					} else {
						echo json_encode(['status' => "error", 'msg' => "Error Reject data !!"]);
					}
				} else {
					echo json_encode(['status' => "error", 'msg' => "Please select data for Reject !!"]);
				}
				break;

			case 'approve_usaha':
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];

					//check id in stg
					$idStg = array();
					$SQLID = "select distinct id from $formCode where idstg in ($id) and id is not null";
					$data = $adeQ->select($SQLID);
					foreach ($data as $row) {
						$idStg[] = $row['id'];
					}
					$idMain = implode(",", $idStg);

					$fieldData = array();
					foreach ($qField as $field) {
						if ($field['type_field'] != 'pk')
							$fieldData[] = $field['name_field'];
					}


					$SQLDel = "delete from $stgtable where id in ($idMain)";
					$SQLDel2 = "delete from $formCode where idstg in ($id)";
					$SQLIns = "insert into $stgtable (" . implode(",", $fieldData) . ", id_user_verifikasi, verifikasi_date) select " . implode(",", $fieldData) . ", $_SESSION[userUniqId], now() from $formCode where idstg in ($id)";

					if (count($idStg) > 0) {
						$quer = $adeQ->query($SQLDel);
					}
					$quer2 = $adeQ->query($SQLIns);

					if ($quer2) {
						$quer3 = $adeQ->query($SQLDel2);
						echo json_encode(['status' => "success", 'msg' => "Approvel successfully !!"]);
					} else {
						echo json_encode(['status' => "error", 'msg' => "Error approvel data !!"]);
					}
				} else {
					echo json_encode(['status' => "error", 'msg' => "Please select data for approval !!"]);
				}
				break;


			case 'reject_usaha':
				if (!empty($_POST['id'])) {
					$id = $_POST['id'];

					$SQLDel2 = "delete from $formCode where idstg in ($id)";
					$quer = $adeQ->query($SQLDel2);

					if ($SQLDel2) {
						echo json_encode(['status' => "success", 'msg' => "Reject successfully !!"]);
					} else {
						echo json_encode(['status' => "error", 'msg' => "Error Reject data !!"]);
					}
				} else {
					echo json_encode(['status' => "error", 'msg' => "Please select data for Reject !!"]);
				}
				break;
		}
	} // close $f
}// close session

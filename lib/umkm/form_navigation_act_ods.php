<?php
require "../../config.php";
require "../base/security_login_global.php";
require "../base/db.php";


if (isset($_SESSION['userid'])) {

	if (isset($_GET['f']) and isset($_GET['type'])) {

		$f = $_GET['f'];
		$type = $_GET['type'];
		$v = isset($_GET['v']) ? $_GET['v'] : null;

		$qForm = $adeQ->select($adeQ->prepare(
			"select * from core_forms where idform=%d",
			$f
		));


		$qGroupField = $adeQ->select($adeQ->prepare(
			"select groupname, max(id) from core_fields where id_form=%d and active is true and groupname is not null group by groupname order by max(id)",
			$f
		));

		foreach ($qForm as $valForm) {
			$formName = $valForm['formname'];
			$formCode = $valForm['formcode'];
			$formDesc = $valForm['description'];
		}

		$type1 = array('text', 'email', 'password', 'number');
		$type2 = array('select');
		$type3 = array('checkbox');
		$type4 = array('date');

		$form = '';
		if ($type == 'add') {

			//create slide group expect header
			$groupForm = "";

			foreach ($qGroupField as $group) {
				$qField = $adeQ->select($adeQ->prepare(
					"select * from core_fields where id_form=%d and groupname=%s and active is true order by id",
					$f,
					$group['groupname']
				));

				if ($group['groupname'] == 'header') {
					$formHeader = "<div class='page'>";

					foreach ($qField as $valField) {
						if ($valField['type_field'] == 'nm') {

							$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

							$descField = str_replace("_", " ", $valField['name_field']);
							$descField = str_replace("id ", "", $descField);
							$descField = ucfirst($descField);
							$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
							$default = null;



							if (in_array($valField['type_input'], $type1)) {

								$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' value='$default' class='form-control $valField[name_field]' placeholder='Enter $descField'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type2)) {
								$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
												$default
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type3)) {
								$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								            	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type4)) {
								$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' value='$default' name='$valField[name_field]' placeholder='Enter $descField'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							}
						}
					}
					//close group	
					$formHeader .= '</div>';
				} else {

					$groupForm .= "<div class='page'>";

					foreach ($qField as $valField) {
						if ($valField['type_field'] == 'nm') {

							$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

							$descField = str_replace("_", " ", $valField['name_field']);
							$descField = str_replace("id ", "", $descField);
							$descField = ucfirst($descField);
							$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
							$default = null;

							//SET COOKIES IF CONFIG TRUE
							if ($valField['cookies_action'] == 'read') {
								//GET SESSION READ
								$getValSession = $adeQ->select($adeQ->prepare("select name_field from core_fields where id=%d", $valField['cookies_read_id']));

								if (in_array($valField['type_input'], $type1)) {
									$default = $_SESSION[$getValSession[0]['name_field']];
								} else if (in_array($valField['type_input'], $type2)) {

									preg_match('/(?<=t=).*/', $valField['link_type_input'], $dbSelect);
									$dbS = str_replace("&filter=all", "", $dbSelect[0]);
									$vSelectBox = $adeQ->select("select * from $dbS where text like '%" . $_SESSION[$getValSession[0]['name_field']] . "%'");
									$default = "<option value='" . $vSelectBox[0]['id'] . "'>" . $vSelectBox[0]['text'] . "</option>";
								} else if (in_array($valField['type_input'], $type4)) {
									$default = $_SESSION[$getValSession[0]['name_field']];
								}
							} else {
								if (in_array($valField['type_input'], $type2)) {
									$default = "<option value=''>Please Select $descField</option>";
								}
							}


							if (in_array($valField['type_input'], $type1)) {
								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' value='$default' class='form-control $valField[name_field]' placeholder='Enter $descField'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type2)) {
								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
								            	$default
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type3)) {
								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								            	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type4)) {
								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' value='$default' name='$valField[name_field]' placeholder='Enter $descField'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							}
						}
					}
					//close group	
					$groupForm .= '</div>';
				}
			} // close loop group

			$form .= $formHeader;
			$form .= $groupForm;
		}

		$form .= "<input type='hidden' class='form-control form$type' name='formType' value='$type'>";

		$type = ucfirst($type) . " $formDesc";


		$arr = array(
			'type' => $type,
			'data' => $form
		);

		echo json_encode($arr);
	} // close $f and $type
}// close session

<?php
require "../../config.php";
require "../base/security_login.php";
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

			//create slide group expect header\
			$formHeader = '';
			$groupForm = "
			<div class='row col-md-12'>
			  <div class='nav-tabs-custom'>
				<ul class='nav nav-tabs'>
			";
			foreach ($qGroupField as $group) {
				if ($group['groupname'] != 'header') {
					$groupForm .= "
						<li class='nav-item'>
						    <a class='nav-link' id='$group[groupname]-tab' data-toggle='tab' href='#" . str_replace(" ", "_", $group['groupname']) . "' role='tab' aria-controls='$group[groupname]' aria-selected='true'>$group[groupname]</a>
						</li>
					";
				}
			}
			$groupForm .= "
					</ul>
					<div class='tab-content'>
			";


			foreach ($qGroupField as $group) {
				$qField = $adeQ->select($adeQ->prepare(
					"select * from core_fields where id_form=%d and groupname=%s and active is true order by id",
					$f,
					$group['groupname']
				));

				if ($group['groupname'] == 'header') {
					$formHeader .= "<div class='row group-$group[groupname]'>";

					foreach ($qField as $valField) {
						if ($valField['type_field'] == 'nm') {

							$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

							$descField = str_replace("_", " ", $valField['name_field']);
							$descField = str_replace("id ", "", $descField);
							$descField = ucfirst($descField);
							$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;
							$default = null;

							//SET COOKIES IF CONFIG TRUE
							if (in_array($valField['type_input'], $type2)) {
								$default = "<option value=''>Please Select $descField</option>";
							}

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

					$groupForm .= "<div class='tab-pane row' id='" . str_replace(" ", "_", $group['groupname']) . "'>";

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

			$groupForm .= '</div></div></div>'; //close content tabs
			$form .= $formHeader;
			$form .= $groupForm;
		} else if ($type == 'edit') {

			//create slide group expect header\
			$value = $adeQ->select($adeQ->prepare("select * from $formCode where id=%s", $v));


			$formHeader = '';
			$groupForm = "
			<div class='row col-md-12'>
			  <div class='nav-tabs-custom'>
				<ul class='nav nav-tabs'>
			";
			foreach ($qGroupField as $group) {
				if ($group['groupname'] != 'header') {
					$groupForm .= "
						<li class='nav-item'>
						    <a class='nav-link' id='$group[groupname]-tab' data-toggle='tab' href='#" . str_replace(" ", "_", $group['groupname']) . "' role='tab' aria-controls='$group[groupname]' aria-selected='true'>$group[groupname]</a>
						</li>
					";
				}
			}

			$groupForm .= "
					</ul>
					<div class='tab-content'>
			";


			foreach ($qGroupField as $group) {
				$qField = $adeQ->select($adeQ->prepare(
					"select * from core_fields where id_form=%d and groupname=%s and active is true order by id",
					$f,
					$group['groupname']
				));

				if ($group['groupname'] == 'header') {
					$formHeader .= "<div class='row group-$group[groupname]'>";

					foreach ($qField as $valField) {
						if ($valField['type_field'] == 'nm') {

							$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

							$descField = str_replace("_", " ", $valField['name_field']);
							$descField = str_replace("id ", "", $descField);
							$descField = ucfirst($descField);
							$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;

							if (in_array($valField['type_input'], $type1)) {
								$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' value='" . $value[0][$valField['name_field']] . "' placeholder='Enter $descField'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type2)) {
								preg_match('/(?<=t=).*/', $valField['link_type_input'], $dbSelect);
								$dbS = str_replace("&filter=all", "", $dbSelect[0]);
								$vSelectBox = $adeQ->select($adeQ->prepare("select * from $dbS where id=%s", $value[0][$valField['name_field']]));
								$optionDefault = '';
								foreach ($vSelectBox as $data) {
									$optionDefault = "<option value='" . $value[0][$valField['name_field']] . "' selected>" . $vSelectBox[0]['text'] . "</option>";
								}
								
								$formHeader .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
												$optionDefault
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type3)) {
								preg_match('/(?<=t=).*/', $valField['link_type_input'], $dbSelect);
								$dbS = str_replace("&filter=all", "", $dbSelect[0]);
								$vSelectBox = $adeQ->select("select * from $dbS where id in (" . $value[0][$valField['name_field']] . ")");
								$sQ = '';
								foreach ($vSelectBox as $key) {
									$sQ .= "<option value='$key[id]' selected>$key[text]</option>";
								}

								$formHeader .= "
										<div class='form-group grp$valField[name_field]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								     			$sQ       	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type4)) {
								$date = (empty($value[0][$valField['name_field']])) ? null : date($datePHP, strtotime($value[0][$valField['name_field']]));
								$formHeader .= "
										<div class='form-group grp$valField[name_field]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='Enter $descField' value='$date'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							}
						}
					}
					//close group	
					$formHeader .= '</div>';
				} else {

					$groupForm .= "<div class='tab-pane row' id='" . str_replace(" ", "_", $group['groupname']) . "'>";

					foreach ($qField as $valField) {
						if ($valField['type_field'] == 'nm') {

							$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

							$descField = str_replace("_", " ", $valField['name_field']);
							$descField = str_replace("id ", "", $descField);
							$descField = ucfirst($descField);
							$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;

							if (in_array($valField['type_input'], $type1)) {
								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' value=\"" . $value[0][$valField['name_field']] . "\" placeholder='Enter $descField'>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type2)) {
								preg_match('/(?<=t=).*/', $valField['link_type_input'], $dbSelect);
								$dbS = str_replace("&filter=all", "", $dbSelect[0]);
								$selectted = "";

								if ($value[0][$valField['name_field']] != "") {
									$vSelectBox = $adeQ->select($adeQ->prepare("select * from $dbS where id=%s", $value[0][$valField['name_field']]));
									if (count($vSelectBox) == 0) {
										$selectted = "<option value='' selected>Please Select</option>";
									} else {
										$selectted = "<option value='" . $value[0][$valField['name_field']] . "' selected>" . $vSelectBox[0]['text'] . "</option>";
									}
								} else {
									$selectted = "<option value='' selected>Please Select</option>";
								}


								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
								            	$selectted
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type3)) {
								preg_match('/(?<=t=).*/', $valField['link_type_input'], $dbSelect);
								$dbS = str_replace("&filter=all", "", $dbSelect[0]);
								$vSelectBox = $adeQ->select("select * from $dbS where id in (" . $value[0][$valField['name_field']] . ")");
								$sQ = '';
								foreach ($vSelectBox as $key) {
									$sQ .= "<option value='$key[id]' selected>$key[text]</option>";
								}

								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
								     			$sQ       	
								            </select>
								            <span class='help-block err$valField[name_field]'></span>
								        </div>
									";
							} elseif (in_array($valField['type_input'], $type4)) {
								$date = (empty($value[0][$valField['name_field']])) ? null : date($datePHP, strtotime($value[0][$valField['name_field']]));
								$groupForm .= "
										<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
								            <label for='$valField[name_field]'>$descField $mustBeVall</label>
								            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='Enter $descField' value='$date'>
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

			$qField = $adeQ->select($adeQ->prepare(
				"select * from core_fields where id_form=%d and active is true and type_field in ('pk', 'fk') order by id",
				$f
			));

			foreach ($qField as $field) {
				$formID = "
				   <input type='hidden' class='form-control $field[name_field]' name='$field[name_field]' value='" . $value[0][$field['name_field']] . "'>
					";
			}


			$groupForm .= '</div></div></div>'; //close content tabs
			$form .= $formHeader;
			$form .= $groupForm;
			$form .= $formID;
		} else if ($type == 'delete') {
			$qField = $adeQ->select($adeQ->prepare(
				"select * from core_fields where id_form=%d and active is true order by id",
				$f
			));

			$value = $adeQ->select($adeQ->prepare("select * from $formCode where id=%s", $v));

			$form .= "<p>Apakah anda yakin ingin menghapus data ini ?</p>";

			foreach ($qField as $valField) {
				if ($valField['type_field'] == 'pk') {
					$form .= "
					   <input type='hidden' class='form-control $valField[name_field]' name='$valField[name_field]' value='" . $value[0][$valField['name_field']] . "'>
						";
				}
			}
		} else if ($type == 'search') {
			$selectForm = "<select class='form-control filter' name='filter[]'>";
			foreach ($qField as $valField) {
				if ($valField['type_field'] != 'pk') {
					$selectForm .= "<option value='$valField[name_field]'>$valField[name_field]</option>";
				}
			}
			$selectForm .= "</select>";

			$logicForm = "<select class='form-control logic' name='logic[]'>";
			$qLogic = $adeQ->select("select * from core_logic");
			foreach ($qLogic as $logic) {
				$logicForm .= "<option value='$logic[logic]'>$logic[description]</option>";
			}
			$logicForm .= "</select>";

			$form .= "
				<button id='b1' type='button' class='btn add-more' type='button'>Klik untuk menambah filter </button>
				<input type='hidden' class='queryFilter'/>
				<br>
				<div class='formFilter'>
					<div class='row formRow'>
						<div class='col-md-4'>
							$selectForm
						</div>
						<div class='col-md-2'>
							$logicForm
						</div>
						<div class='col-md-4'>
							<input type='text' class='form-control valueFilter' name='valueFilter[]'/> 
						</div>
						
					</div>
				</div>
			";
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

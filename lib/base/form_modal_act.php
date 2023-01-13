<?php
require "../../config.php";
require "security_login.php";
require "db.php";


if (isset($_SESSION['userid'])) {

	if (isset($_GET['f']) and isset($_GET['type'])) {

		$f = $_GET['f'];
		$type = $_GET['type'];
		$v = isset($_GET['v']) ? $_GET['v'] : null;

		$qForm = $adeQ->select($adeQ->prepare(
			"select * from core_forms where idform=%d",
			$f
		));

		$qField = $adeQ->select($adeQ->prepare(
			"select * from core_fields where id_form=%d and active is true order by id",
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
			foreach ($qField as $valField) {
				if ($valField['type_field'] == 'nm') {

					$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

					$descField = str_replace("_", " ", $valField['name_field']);
					if (in_array($valField['type_input'], $type1)) {
						$form .= "
							<div class='form-group grp$valField[name_field]'>
					            <label for='$valField[name_field]'>$descField $mustBeVall</label>
					            <input type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' placeholder='Enter $descField'>
					            <span class='help-block err$valField[name_field]'></span>
					        </div>
						";
					} elseif (in_array($valField['type_input'], $type2)) {
						$form .= "
							<div class='form-group grp$valField[name_field]'>
					            <label for='$valField[name_field]'>$descField $mustBeVall</label>
					            <select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
					            	<option value=''>Please Select $descField</option>
					            </select>
					            <span class='help-block err$valField[name_field]'></span>
					        </div>
						";
					} elseif (in_array($valField['type_input'], $type3)) {
						$form .= "
							<div class='form-group grp$valField[name_field]'>
					            <label for='$valField[name_field]'>$descField $mustBeVall</label>
					            <select class='form-control $valField[name_field]' name='$valField[name_field][]' multiple='multiple' style='width: 100%;'>
					            	
					            </select>
					            <span class='help-block err$valField[name_field]'></span>
					        </div>
						";
					} elseif (in_array($valField['type_input'], $type4)) {
						$form .= "
							<div class='form-group grp$valField[name_field]'>
					            <label for='$valField[name_field]'>$descField $mustBeVall</label>
					            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='Enter $descField'>
					            <span class='help-block err$valField[name_field]'></span>
					        </div>
						";
					}
				}
			}
		} else if ($type == 'edit') {
			$value = $adeQ->select($adeQ->prepare("select * from $formCode where id=%d", $v));


			foreach ($qField as $valField) {
				if ($valField['type_field'] == 'nm') {

					$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

					$descField = str_replace("_", " ", $valField['name_field']);
					if (in_array($valField['type_input'], $type1)) {
						$form .= "
							<div class='form-group grp$valField[name_field]'>
					            <label for='$valField[name_field]'>$descField $mustBeVall</label>
					            <input type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' placeholder='Enter $descField' value='" . $value[0][$valField['name_field']] . "'>
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

						$form .= "
							<div class='form-group grp$valField[name_field]'>
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

						$form .= "
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
						$form .= "
							<div class='form-group grp$valField[name_field]'>
					            <label for='$valField[name_field]'>$descField $mustBeVall</label>
					            <input type='text' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' placeholder='Enter $descField' value='$date'>
					            <span class='help-block err$valField[name_field]'></span>
					        </div>
						";
					}
				} else {
					$form .= "
					   <input type='hidden' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' value='" . $value[0][$valField['name_field']] . "'>
						";
				}
			}
		} else if ($type == 'delete') {
			$value = $adeQ->select($adeQ->prepare("select * from $formCode where id=%d", $v));

			$form .= "<p>Apakah anda yakin ingin menghapus data ini ?</p>";

			foreach ($qField as $valField) {
				if ($valField['type_field'] == 'pk') {
					$form .= "
					   <input type='hidden' class='datepicker form-control $valField[name_field]' name='$valField[name_field]' value='" . $value[0][$valField['name_field']] . "'>
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

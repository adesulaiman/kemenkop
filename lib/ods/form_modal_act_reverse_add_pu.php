<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";


if(isset($_SESSION['userid']))
{

	if(isset($_GET['f']) and isset($_GET['type']))
	{

		$f = $_GET['f'];
		$type = $_GET['type'];
		$v = isset($_GET['v']) ? $_GET['v'] : null;
		$ktp = $_GET['ktp'];

		$qForm = $adeQ->select($adeQ->prepare(
		    "select * from core_forms where idform=%d", $f
		));


		$qGroupField = $adeQ->select($adeQ->prepare(
		    "select distinct groupname from core_fields where id_form=%d and active is true and groupname is not null", $f
		));

		foreach($qForm as $valForm)
		{
		  $formName = $valForm['formname'];
		  $formCode = $valForm['formcode'];
		  $formDesc = $valForm['description'];
		}

		$type1 = array('text', 'email', 'password', 'number');
		$type2 = array('select');
		$type3 = array('checkbox');
		$type4 = array('date');

		$sttKtp = '';

		$form = '';
		if($type == 'edit')
		{
			
			//create slide group expect header\
			$value = $adeQ->select($adeQ->prepare("select * from $formCode where nik_ktp=%s", $ktp));
			$sttKtp = count($value);

			if($sttKtp > 0){
				$formHeader = '';
				$groupForm = "
				<div class='row col-md-12'>
				<div class='nav-tabs-custom'>
					<ul class='nav nav-tabs'>
				";
				foreach($qGroupField as $group)
				{
					if($group['groupname'] != 'header')
					{
						$groupForm .= "
							<li class='nav-item'>
								<a class='nav-link' id='$group[groupname]-tab' data-toggle='tab' href='#$group[groupname]' role='tab' aria-controls='$group[groupname]' aria-selected='true'>$group[groupname]</a>
							</li>
						";
					}
				}
				
				$groupForm .= "
						</ul>
						<div class='tab-content'>
				";


				foreach($qGroupField as $group)
				{
					$qField = $adeQ->select($adeQ->prepare(
						"select * from core_fields where id_form=%d and groupname=%s and active is true order by id", $f, $group['groupname']
					));

					if($group['groupname'] == 'header')
					{
						$formHeader .= "<div class='row group-$group[groupname]'>";

						foreach($qField as $valField)
							{
								if($valField['type_field'] == 'nm')
								{

									$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

									$descField = str_replace("_", " ", $valField['name_field']);
									$descField = str_replace("id ", "", $descField);
									$descField = ucfirst($descField);
									$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;

									if(in_array($valField['type_input'], $type1))
									{
										$formHeader .= "
											<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
												<label for='$valField[name_field]'>$descField $mustBeVall</label>
												<input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' value='".$value[0][$valField['name_field']]."' placeholder='Enter $descField'>
												<span class='help-block err$valField[name_field]'></span>
											</div>
										";
									}elseif(in_array($valField['type_input'], $type2))
									{
										preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
										$dbS = str_replace("&filter=all", "", $dbSelect[0]);
										$vSelectBox = $adeQ->select($adeQ->prepare("select * from $dbS where id=%s", $value[0][$valField['name_field']]));

										$formHeader .= "
											<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
												<label for='$valField[name_field]'>$descField $mustBeVall</label>
												<select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
													<option value='".$value[0][$valField['name_field']]."' selected>".$vSelectBox[0]['text']."</option>
												</select>
												<span class='help-block err$valField[name_field]'></span>
											</div>
										";
									}elseif(in_array($valField['type_input'], $type3))
									{
										preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
										$dbS = str_replace("&filter=all", "", $dbSelect[0]);
										$vSelectBox = $adeQ->select("select * from $dbS where id in (".$value[0][$valField['name_field']].")");
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
									}elseif(in_array($valField['type_input'], $type4))
									{
										$date = (empty($value[0][$valField['name_field']])) ? null : date($datePHP ,strtotime($value[0][$valField['name_field']]));
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

					}else{

						$groupForm .= "<div class='tab-pane row' id='$group[groupname]'>";

						foreach($qField as $valField)
							{
								if($valField['type_field'] == 'nm')
								{

									$mustBeVall = ($valField['validate'] == 't') ? "<span style='color:red'>*</span>" : "";

									$descField = str_replace("_", " ", $valField['name_field']);
									$descField = str_replace("id ", "", $descField);
									$descField = ucfirst($descField);
									$mask = ($valField['format_type'] != null) ? "data-inputmask='\"mask\": \"[$valField[format_type]]\"' data-mask" : null;

									if(in_array($valField['type_input'], $type1))
									{
										$groupForm .= "
											<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
												<label for='$valField[name_field]'>$descField $mustBeVall</label>
												<input $mask type='$valField[type_input]' name='$valField[name_field]' class='form-control $valField[name_field]' value='".$value[0][$valField['name_field']]."' placeholder='Enter $descField'>
												<span class='help-block err$valField[name_field]'></span>
											</div>
										";
									}elseif(in_array($valField['type_input'], $type2))
									{
										preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
										$dbS = str_replace("&filter=all", "", $dbSelect[0]);
										$vSelectBox = $adeQ->select($adeQ->prepare("select * from $dbS where id=%s", $value[0][$valField['name_field']]));

										$groupForm .= "
											<div class='form-group grp$valField[name_field] col-md-$valField[position_md]'>
												<label for='$valField[name_field]'>$descField $mustBeVall</label>
												<select class='form-control $valField[name_field]' name='$valField[name_field]' style='width: 100%;'>
													<option value='".$value[0][$valField['name_field']]."' selected>".$vSelectBox[0]['text']."</option>
												</select>
												<span class='help-block err$valField[name_field]'></span>
											</div>
										";
									}elseif(in_array($valField['type_input'], $type3))
									{
										preg_match('/(?<=t=).*/',$valField['link_type_input'], $dbSelect);
										$dbS = str_replace("&filter=all", "", $dbSelect[0]);
										$vSelectBox = $adeQ->select("select * from $dbS where id in (".$value[0][$valField['name_field']].")");
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
									}elseif(in_array($valField['type_input'], $type4))
									{
										$date = (empty($value[0][$valField['name_field']])) ? null : date($datePHP ,strtotime($value[0][$valField['name_field']]));
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
						"select * from core_fields where id_form=%d and active is true and type_field = 'pk' order by id", $f
					));

				foreach ($qField as $field) {
					$formID = "
					<input type='hidden' class='form-control $field[name_field]' name='$field[name_field]' value='".$value[0][$field['name_field']]."'>
						";
				}


				$groupForm .= '</div></div></div>'; //close content tabs
				$form .= $formHeader;
				$form .= $groupForm;
				$form .= $formID;

			}




		}

		$form .= "<input type='hidden' class='form-control form$type' name='formType' value='$type'>";

		$type = ucfirst($type) ." $formDesc";


		$arr = array(
			'type' => $type,
			'data' => $form,
			'statusKTP' => $sttKtp
		);

		echo json_encode($arr);

	} // close $f and $type
}// close session
?>
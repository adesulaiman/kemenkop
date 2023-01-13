<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if(isset($_SESSION['userid']))
{
	if(isset($_GET['idForm']) and isset($_GET['idReg']))
  	{
		$idForm = explode(',', $_GET['idForm']);
		$idReg = $_GET['idReg'];

		$html = "";

		for($i = 0 ; $i < count($idForm) ; $i++){
			$qForm = $adeQ->select($adeQ->prepare(
				"select * from core_forms where idform = %d", $idForm[$i]
			));

			$qField = $adeQ->select($adeQ->prepare(
				"select * from core_fields where id_form = %d and type_field='nm' and active is true order by id", $idForm[$i]
			));

			$qData = $adeQ->select($adeQ->prepare(
				"select * from ".$qForm[0]['formview']." where id_registrasi_online = %d", $idReg
			));

			$html .= "
			<div class='callout callout-success'>
				<h3 style='margin:0' class='text-center'> <b> ".$qForm[0]['description']." </b> </h3>
			</div>
			<table class='table table-striped'>
					<tbody>
			";

			foreach($qData as $dt){
				$columnFix = array();
				
				foreach($qField as $field){
					if(substr( $field['name_field'],0,3) == 'id_')
                    {
						$col =  str_replace("id_", "",  $field['name_field']);  
						$col =  ucfirst(str_replace("_", " ",  $col));
					}else{
						$col =  ucfirst(str_replace("_", " ",  $field['name_field']));;
					}
					
					$html .= "
					<tr class='tr$field[name_field]-$idForm[$i]'>
						<td style='width:30%'><b>$col</b></td>
						<td style='width:40%' class='db$field[name_field]-$idForm[$i]'>".$dt[$field['name_field']]."</td>
						<td style='width:30%' class='api$field[name_field]-$idForm[$i]'></td>
					</tr>
					";
				}
			}

			$html .= "
				</tbody>
			</table>
			";


		}
		
		echo json_encode(["data" => $html]);



	}//close $f $t
}// close session

?>
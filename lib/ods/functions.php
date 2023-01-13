<?php

function get_uniq_by_column($table, $column, $key, $adeQ)
{

	$countKTP = $adeQ->select($adeQ->prepare("select count($column) as cek, $column from $table where $column = %s group by $column", $key));
	
	if($countKTP){
		foreach($countKTP as $cnt)
		{
			$cek = $cnt['cek'];
			$data = $cnt[$column];
		}
	}else{
		$cek = null;
	}
	

	if($cek == null){
		$uniq = $key . '1';
	}else{
		$uniq = $data . ($cek + 1);
	}

	return $uniq;
}



?>
<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";

if(isset($_SESSION['userid']))
{
	if(isset($_GET['f']) and isset($_GET['t']))
  	{
		$t = $_GET['t'];
		$f = $_GET['f'];


		$qField = $adeQ->select($adeQ->prepare(
		    "select * from information_schema.columns where table_name=%s order by ordinal_position", $t
        ));
        

        //filter session area
        $area = array();
        $qArea = $adeQ->select(
            "select replace(tablearea, 'select_vw_ref', 'id') || '_domisili' as col, idarea from core_rolearea 
            where iduser=$_SESSION[userUniqId] and idarea <> 0"
        );
        foreach($qArea as $dt){
            $area[] = "$dt[col] = $dt[idarea]";
        }

		## Read value
		$draw = $_POST['draw'];
		$row = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
		$searchValue = $_POST['search']['value']; // Search value

		$query = isset($_POST['query']) ? $_POST['query'] : null;

		if($_SESSION['typegroup'] == 'KOPERASI'){
			if(empty($query))
			{
				if(count($area) > 0){
					$where = "where " . implode(" and ", $area);
				}else{
					$where = "";
				}
			}else{
				if(count($area) > 0){
					$where = "where ". implode(" and ", $area) . " and " . $query;
				}else{
					$where = "where  $query";
				}
			}
		}else if ($_SESSION['typegroup'] == 'UMKM'){
			if(empty($query))
			{
				$where = "where nik_ktp='$_SESSION[userid]'";
			}else{
				$where = "where nik_ktp='$_SESSION[userid]' and " . $query;
			}
		}

		

		## Total number of records without filtering
		$sel = $adeQ->select("select count(*) as allcount from $t $where");
		$totalRecords = $sel[0]['allcount'];

		## Total number of records with filtering
		$sel = $adeQ->select("select count(*) as allcount from $t $where"/*.$searchQuery*/);
		$totalRecordwithFilter = $sel[0]['allcount'];

		## Fetch records
		if($dbRDBMS == 'mysql')
		{
			if($rowperpage == -1)
			{
				$limitQuery = '';
			}else{
				$limitQuery = " limit ".$row.",".$rowperpage;
			}
		}elseif($dbRDBMS == 'pgsql')
		{
			if($rowperpage == -1)
			{
				$limitQuery = '';
			}else{
				$limitQuery = " limit ".$rowperpage." offset ".$row;
			}
			
		}

		$empQuery = "select * from $t $where order by ".$columnName." ".$columnSortOrder.$limitQuery;
		$empRecords = $adeQ->select($empQuery);
		$data = array();


		foreach ($empRecords as $row ) {
			$dataArr = array();
			foreach($qField as $coll)
			{
				if($coll['data_type'] == 'date')
				{
					$dataArr[$coll['column_name']] = date($datePHP, strtotime($row[$coll['column_name']]));
				}else{
					$dataArr[$coll['column_name']] = $row[$coll['column_name']];
				}
				
			}
			$data[] = $dataArr;
		}



		## Response
		$response = array(
		  "draw" => intval($draw),
		  "recordsTotal" => $totalRecords,
		  "recordsFiltered" => $totalRecordwithFilter,
		  "data" => $data
		);

		echo json_encode($response);
	}//close $f $t
}// close session

?>
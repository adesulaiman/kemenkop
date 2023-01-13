<?php
require "../../config.php";
// require "security_login.php";
require "db.php";

// if(isset($_SESSION['userid']))
// {
if (isset($_GET['filter']) and isset($_GET['t'])) {
	$t = $_GET['t'];
	$f = $_GET['filter'];
	$flag = isset($_GET['flag']) ? $_GET['flag'] : null;
	$roleFilter = isset($_GET['rolefilter']) ? $_GET['rolefilter'] : null;
	$roleKTP = isset($_GET['roleKTP']) ? $_GET['roleKTP'] : null;


	$search = isset($_GET['search']) ? $_GET['search'] : null;
	$result = array();


	$default = array('id' => '0', 'text' => 'Select All');
	array_push($result, $default);
	session_start();


	if ($roleFilter == 'yes') {


		$getRoleArea = $adeQ->select("select * from core_rolearea where iduser=$_SESSION[userUniqId]");

		foreach ($getRoleArea as $value) {

			if ($value['tablearea'] == $t) {

				$roleFilter = ($value['idarea'] == 0) ? "id" : $value['idarea'];

				if ($f == 'all') {

					$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
					$q = $adeQ->select("select * from $t where id=$roleFilter $qfind limit 100");



					foreach ($q as $data) {
						array_push($result, $data);
					}

					echo json_encode(['results' => $result]);
				} else {
					$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
					$q = $adeQ->select("select * from $t where filter='$f' and id=$roleFilter $qfind limit 100");


					foreach ($q as $data) {
						array_push($result, $data);
					}

					echo json_encode(['results' => $result]);
				}
			}
		}
	} else if ($roleKTP == 'yes') {

		if ($_SESSION['typegroup'] == 'KOPERASI') {
			$area = array();
			$qArea = $adeQ->select(
				"select replace(tablearea, 'select_vw_ref', 'id') as col, idarea from core_rolearea 
					where iduser=$_SESSION[userUniqId] and idarea <> 0"
			);
			foreach ($qArea as $dt) {
				$area[] = "$dt[col] = $dt[idarea]";
			}

			$filterAreabyKTP = '';
			if (count($area) > 0) {
				$filterAreabyKTP =	"and " . implode(" and ", $area);
			}
		} else if ($_SESSION['typegroup'] == 'UMKM') {
			$filterAreabyKTP = " and id=$_SESSION[userid]";

		}



		if ($f == 'all') {
			$qfind = (empty($search)) ? "" : " and lower(text) like '%" . strtolower($search) . "%'";
			$q = $adeQ->select("select * from $t where 1=1 $qfind $filterAreabyKTP limit 100");


			foreach ($q as $data) {
				array_push($result, $data);
			}

			echo json_encode(['results' => $result]);
		} else {
			$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
			$q = $adeQ->select("select * from $t where filter='$f' and " . implode(" and ", $area) . " $qfind limit 100");


			foreach ($q as $data) {
				array_push($result, $data);
			}

			echo json_encode(['results' => $result]);
		}
	} else {
		if ($f == 'all') {
			$qfind = (empty($search)) ? "" : "where lower(text) like '%" . strtolower($search) . "%'";
			$q = $adeQ->select("select * from $t $qfind limit 100");

			foreach ($q as $data) {
				array_push($result, $data);
			}

			echo json_encode(['results' => $result]);
		} else {
			$qfind = (empty($search)) ? "" : "and lower(text) like '%" . strtolower($search) . "%'";
			$q = $adeQ->select("select * from $t where filter='$f' $qfind limit 100");

			foreach ($q as $data) {
				array_push($result, $data);
			}

			echo json_encode(['results' => $result]);
		}
	}
}//close $f
// }//close session

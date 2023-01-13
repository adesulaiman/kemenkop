<?php
require "../../config.php";
require "db.php";
require "security_login_global.php";



if(isset($_SESSION['userid']))
{
	$username = $_POST['username'];
	$pass = $_POST['pass'];
	$repass = $_POST['repass'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];

	$stt = true;
	$msg = '';
	$validate = array();

	//cek null data
	if($username == null)
	{
		$stt = false;
		$validate[] = array(
			'field' => 'username',
			'err' => 'validate',
			'msg' => 'Username harus terisi'
		);
	}else{

		$validate[] = array(
			'field' => 'username',
			'err' => 'success',
			'msg' => ''
		);

		if($pass == null and $repass == null){
			//condition if password not change
			$qUpdate = $adeQ->query("update core_user set 
				username='$username',
				firstname='$firstname',
				lastname='$lastname'
				where id=$_SESSION[userUniqId]");

			$msg = 'User Berhasil Di perbarui';

			if(!$qUpdate){
				$msg = 'Error Update Data';
				$stt = false;
			}
		}else{
			//condition if password want to change
			if($pass == $repass){
				$pass = $hasher->HashPassword($pass);
				$qUpdate = $adeQ->query("update core_user set 
					username='$username',
					userpass='$pass',
					firstname='$firstname',
					lastname='$lastname'
					where id=$_SESSION[userUniqId]");
				
				$msg = 'User Berhasil Di perbarui';

				if(!$qUpdate){
					$msg = 'Error Update Data';
					$stt = false;
				}

				$validate[] = array(
					'field' => 'repass',
					'err' => 'success',
					'msg' => ''
				);

			}else{
				$stt = false;
				$validate[] = array(
					'field' => 'repass',
					'err' => 'validate',
					'msg' => 'Ulangi Password harus sama dengan Password'
				);
			}

		}
	}

	echo json_encode(['status'=> $stt, 'validate' => $validate, 'msg' => $msg]);

}//close session



?>
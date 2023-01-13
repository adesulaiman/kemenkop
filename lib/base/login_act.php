<?php
require "../../config.php";
require "db.php";
session_start();



if(!empty($_GET['userid']) and !empty($_GET['pass']))
{
	$userid = $_GET['userid'];
	$pass = $_GET['pass'];

	$q = $adeQ->select($adeQ->prepare("select * from core_user where isactive=1 and userid=%s", $userid));

		$status = '';
		$page = '';
		$data = array();

		if(count($q) > 0)
		{
			if($userid == $q[0]['userid'])
			{
				$getRF = $adeQ->select($adeQ->prepare("select 
									split_part(split_part(links, '=', 2), '&',1) as form
									from core_vw_rolemenus
									where iduser=%d", $q[0]['idgroup']));

				$roleForm = array();
				foreach ($getRF as $RF ) {
					array_push($roleForm, $RF['form']);
				}

				if($hasher->CheckPassword($pass,$q[0]['userpass']))
				{

					if($maxTryLogin != 99)
					{
						if($q[0]['wrongpass'] <= $maxTryLogin)
						{
							$fl = 'success';
							$status = 'Login Berhasil';
							$page = './';
							$_SESSION['userid'] = $q[0]['userid'];
							$_SESSION['id'] = $q[0]['id'];
							$_SESSION['userUniqId'] = $q[0]['idgroup'];
							$_SESSION['username'] = $q[0]['username'];
							$_SESSION['typegroup'] = $q[0]['typegroup'];
							$_SESSION['roleForm'] = $roleForm;
						}else{
							$fl = 'error';
							$status = 'Login Gagal, Akun Anda Terkunci !';
						}
					}else{
						$fl = 'success';
						$status = 'Login Berhasil';
						$page = './';
						$_SESSION['userid'] = $q[0]['userid'];
						$_SESSION['id'] = $q[0]['id'];
						$_SESSION['userUniqId'] = $q[0]['idgroup'];
						$_SESSION['username'] = $q[0]['username'];
						$_SESSION['typegroup'] = $q[0]['typegroup'];
						$_SESSION['roleForm'] = $roleForm;
					}


					

				}else{
					
					$fl = 'error';
					$status = 'Login Gagal, Password Salah !';

					if($maxTryLogin != 99)
					{
						$i = $q[0]['wrongpass'] + 1;
						$updWrongPass = $adeQ->query("update core_user set wrongpass=$i where id=".$q[0]['id']);
					}

				}
			}else{
				$fl = 'error';
				$status = 'Login Gagal, User Tidak Terdaftar !';
			}
		}else{
			$fl = 'error';
			$status = 'Login Gagal, User Tidak Terdaftar !';
		}

		echo json_encode(['status'=>$fl, 'text'=>$status, 'page'=>$page]);
}else{
	echo json_encode(['status'=>'error', 'text'=>'Mohon isi userid dan password', 'page'=>'']);
}
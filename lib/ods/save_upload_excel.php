<?php
require "../../config.php";
require "../base/security_login.php";
require "../base/db.php";
require "functions.php";

require '../../plugins/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if(isset($_SESSION['userid']))
{
	if(isset($_POST['type']) and isset($_POST['f']))
  	{
		$type = $_POST['type'];
		$form = $_POST['f'];

		if($type == 'usaha'){
			$stt = true;
			//UPLOAD PDF DATA
			$filePdf = array(
				"data-usaha_upload" => isset($_FILES["data-usaha_upload"]) ? $_FILES["data-usaha_upload"] : null
			);
			$insPdf = array();
	
			foreach($filePdf as $val => $data){
			
				if(isset($data)){
					//CEK NAME AND EXT
					$nameFile = $data['name'];
					$cekhack = explode('.', $nameFile);
					$cekExt = array("php", "js", "php5");
					$isExt = true;
					
					for($l=0 ; $l < count($cekhack) ; $l++){
						if(in_array(strtolower($cekhack[$l]), $cekExt)){
							$isExt = false;
						}
					}
	
					if($data["size"] > 5000000){
						$stt = false;
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Error file size, size must lower then ' . 5000000 / 1000000 . 'MB'
						);
					}else if($isExt == false){
						$stt = false;
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Error file upload, inject detected'
						);
					}else{
						$nameFile = uniqid() . "_$nameFile";
						$updFile = "../../assets/upload/" . $nameFile;
						if (move_uploaded_file($data["tmp_name"], $updFile)) {
							chmod($updFile, 777);
							$insPdf[$val] = "'$nameFile'";
							$insPdf["path_".$val] = $updFile;
							$validate[] = array(
								'field' => $val,
								'err' => '',
								'msg' => 'Upload Success'
							);	
						} else {
							$stt = false;
							$validate[] = array(
								'field' => $val,
								'err' => 'validate',
								'msg' => 'Error file upload, Upload file not allowed in your system'
							);
						}
					}
				}else{
					if(in_array($val, ['pdf2_upload', 'pdf3_upload', 'pdf4_upload'])){
						$insPdf[$val] = "NULL";
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Data must upload !!'
						);
					}else{
						$stt = false;
						$insPdf[$val] = "NULL";
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Harus melampirkan file Excel !!'
						);
					}
				}
			}


			if($stt){

				$inputFileType = 'Xlsx';
				$inputFileName = $insPdf["path_data-usaha_upload"];

				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				$reader->setReadDataOnly(true);
				$spreadsheet = $reader->load($inputFileName);
				$data = $spreadsheet->getSheet(0)->toArray();

				$reject = array();
				$i = 1;

				foreach($data as $val){
					if($i > 2){

						if($val[0] != ''){
							$nik = $val[0];
							$nib = $val[1];
							
							//get role area
							$sttsRole = true;
							if($area['id_provinsi'] > 0){
								if($val[10] != $area['id_provinsi']){
									$sttsRole = false;
									$reject[] =  "NIB $nib Di luar akses role wilayah !!";
								}else{

									if($area['id_kabupaten_kota'] > 0){
										if($val[11] != $area['id_kabupaten_kota']){
											$sttsRole = false;
											$reject[] =  "NIB $nib Di luar akses role wilayah !!";
										}else{
											if($area['id_kecamatan'] > 0){
												if($val[12] != $area['id_kecamatan']){
													$sttsRole = false;
													$reject[] =  "NIB $nib Di luar akses role wilayah !!";
												}else{
													if($area['id_kelurahan'] > 0){
														if($val[13] != $area['id_kelurahan']){
															$sttsRole = false;
															$reject[] =  "NIB $nib Di luar akses role wilayah !!";
														}
													}else{

														//cek kelurahan
														$getkel = $adeQ->select("select count(1) as cek from data_ref_kelurahan where id_kecamatan=".$val[12]." and id=".$val[13]);
														if($getkel[0]['cek'] == 0){
															$sttsRole = false;
															$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
														}
						
													}
												}
											}else{

												//cek kecamatan
												$getkec = $adeQ->select("select count(1) as cek from data_ref_kecamatan where id_kabupaten_kota=".$val[11]." and id=".$val[12]);
												if($getkec[0]['cek'] == 0){
													$sttsRole = false;
													$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
												}
						
											}
										}
									}else{
										//cek kab/kota
										$getkabKota = $adeQ->select("select count(1) as cek from data_ref_kabupaten_kota where id_provinsi=".$val[10]." and id=".$val[11]);
										if($getkabKota[0]['cek'] == 0){
											$sttsRole = false;
											$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
										}
						
									}
								}
							}
							
							
							$id_bentuk_usaha = $val[2];
							$nama_usaha = str_replace("'", "''", $val[3]) ;
							$nama_brand = str_replace("'", "''", $val[4]) ;
							$nama_izin_usaha = str_replace("'", "''", $val[5]) ;
							$no_izin_usaha = str_replace("'", "''", $val[6]);
							$tgl_penerbit_izin_usaha = gmdate("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($val[7]));
							$tgl_mulai_usaha = gmdate("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($val[8]));
							$npwp_usaha = str_replace("'", "''", $val[9]) ;
							$id_provinsi = str_replace("'", "''", $val[10]) ;
							$id_kabupaten_kota = $val[11];
							$id_kecamatan = $val[12];
							$id_kelurahan = $val[13];
							$kodepos = $val[14];
							$alamat_usaha = str_replace("'", "''", $val[15]) ;
							$rt_usaha = $val[16];
							$rw_usaha = $val[17];
							$tlp_kantor = $val[18];
							$tlp_mobile = $val[19];
							$fax = $val[20];
							$email_usaha = $val[21];
							$website = $val[22];
							$media_sosial = $val[23];
							$nama_akun_media_sosial = $val[24];
							$catatan = str_replace("'", "''", $val[25]);
							$tanggal_pendataan = gmdate("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($val[26]));
							$nama_sumber_pendata = str_replace("'", "''", $val[27]);
							$id_jenis_usaha = $val[28];
							$id_kbli = $val[29];

							$getIDKTP = $adeQ->select($adeQ->prepare("select id from data_mst_pemilik_usaha where nik_ktp=%s",$nik));

							if(count($getIDKTP) > 0){
								$id_pemilik_usaha = $getIDKTP[0]['id'];

								$getNIB = $adeQ->select($adeQ->prepare("select id from data_mst_usaha where nib=%s",$nib));
								if(count($getNIB) == 0){

									if($sttsRole){
										$ins = $adeQ->query("insert into data_mst_usaha (
											id_pemilik_usaha, id_bentuk_usaha, nama_usaha, nama_izin_usaha, no_izin_usaha, tgl_penerbit_izin_usaha, tgl_mulai_usaha, npwp_usaha, id_provinsi, id_kabupaten_kota, id_kecamatan, id_kelurahan, kodepos, alamat_usaha, rt_usaha, rw_usaha, tlp_kantor, tlp_mobile, fax, email_usaha, website, media_sosial, nama_akun_media_sosial, nama_brand, catatan, id_user_insert, tanggal_pendataan, insert_date,  nama_sumber_pendata, id_jenis_usaha, id_kbli, nib
										) values (
											$id_pemilik_usaha, $id_bentuk_usaha, '$nama_usaha', '$nama_izin_usaha', '$no_izin_usaha', '$tgl_penerbit_izin_usaha', '$tgl_mulai_usaha', '$npwp_usaha', $id_provinsi, $id_kabupaten_kota, $id_kecamatan, $id_kelurahan, '$kodepos', '$alamat_usaha', '$rt_usaha', '$rw_usaha', '$tlp_kantor', '$tlp_mobile', '$fax', '$email_usaha', '$website', '$media_sosial', '$nama_akun_media_sosial', '$nama_brand', '$catatan', 999, '$tanggal_pendataan', now(), '$nama_sumber_pendata', $id_jenis_usaha, $id_kbli, $nib
										)");
										if(!$ins){
											$reject[] = $nik;
										}
									}
									
								}else{
									$reject[] = "NIB $nib Sudah Terdaftar !! ";
								}
							}else{
								$reject[] = "NIK $nik tidak ada di list Pemilik Usaha ";
							}
						}
						
					}
					$i++;
				}

				$msg = '';
				// if(count($reject) > 0){
				// 	$msg = "Data berhasil di upload, dengan data reject yang belum memiliki nik di data pemilik usaha, yaitu : \n". implode("\n", $reject);
				// }else{
				// 	$msg = "Data berhasil di upload.";
				// }

				if(count($reject) > 0){
					$msg = "Data berhasil di upload dengan beberapa data reject";
					$csv = "Data berhasil di upload dengan beberapa data reject yaitu : \n". implode("\n", $reject);
					$rej = 1;
				}else{
					$msg = "Data berhasil di upload.";
					$csv = "" ;
					$rej = 0;
				}

				echo json_encode(["status" => true, "msg" => $msg, "csv" => $csv, "rej" => $rej]);

				// echo json_encode(["status" => true, "msg" => $msg]);
	
			}else{
				$msgInfo = '';
				foreach($validate as $dt){
					if(!in_array($dt['field'], ['pdf2_upload', 'pdf3_upload', 'pdf4_upload'])){
						if($dt['err'] == "validate"){
							$msgInfo .= "\nData : ".$dt['field'].', '.$dt['msg'];
						}
					}
				}
				echo json_encode(["status" => false, "msg" => "$msgInfo", "validate" => $validate]);
	
			}

		}else if($type == 'pemilik_usaha'){

			$stt = true;
			//UPLOAD PDF DATA
			$filePdf = array(
				"data-pemilik-usaha_upload" => isset($_FILES["data-pemilik-usaha_upload"]) ? $_FILES["data-pemilik-usaha_upload"] : null
			);
			$insPdf = array();
	
			foreach($filePdf as $val => $data){
			
				if(isset($data)){
					//CEK NAME AND EXT
					$nameFile = $data['name'];
					$cekhack = explode('.', $nameFile);
					$cekExt = array("php", "js", "php5");
					$isExt = true;
					
					for($l=0 ; $l < count($cekhack) ; $l++){
						if(in_array(strtolower($cekhack[$l]), $cekExt)){
							$isExt = false;
						}
					}
	
					if($data["size"] > 5000000){
						$stt = false;
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Error file size, size must lower then ' . 5000000 / 1000000 . 'MB'
						);
					}else if($isExt == false){
						$stt = false;
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Error file upload, inject detected'
						);
					}else{
						$nameFile = uniqid() . "_$nameFile";
						$updFile = "../../assets/upload/" . $nameFile;
						if (move_uploaded_file($data["tmp_name"], $updFile)) {
							chmod($updFile, 777);
							$insPdf[$val] = "'$nameFile'";
							$insPdf["path_".$val] = $updFile;
							$validate[] = array(
								'field' => $val,
								'err' => '',
								'msg' => 'Upload Success'
							);	
						} else {
							$stt = false;
							$validate[] = array(
								'field' => $val,
								'err' => 'validate',
								'msg' => 'Error file upload, Upload file not allowed in your system'
							);
						}
					}
				}else{
					if(in_array($val, ['pdf2_upload', 'pdf3_upload', 'pdf4_upload'])){
						$insPdf[$val] = "NULL";
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Data must upload !!'
						);
					}else{
						$stt = false;
						$insPdf[$val] = "NULL";
						$validate[] = array(
							'field' => $val,
							'err' => 'validate',
							'msg' => 'Harus melampirkan file Excel !!'
						);
					}
				}
			}


			if($stt){

				$inputFileType = 'Xlsx';
				$inputFileName = $insPdf["path_data-pemilik-usaha_upload"];

				$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
				$reader->setReadDataOnly(true);
				$spreadsheet = $reader->load($inputFileName);
				$data = $spreadsheet->getSheet(0)->toArray();

				$reject = array();
				$i = 1;

				//get role area
				$area = array();
				$qArea = $adeQ->select(
					"select replace(tablearea, 'select_vw_ref', 'id') as col, idarea from core_rolearea 
					where iduser=$_SESSION[userUniqId]"
				);
				foreach($qArea as $dt){
					$area[$dt['col']] = $dt['idarea'];
				}

				foreach($data as $val){
					if($i > 2){

						if($val[0] != ''){

							$nik_ktp = $val[0];

							//cek KTP
							$ktpCnt = $adeQ->select("select count(1) as cek from data_mst_pemilik_usaha where nik_ktp='".$val[0]."'");

							if($ktpCnt[0]['cek'] == 1){
								$reject[] =  "NIK $nik_ktp Sudah Ada Di System !!";
							}else{

								$sttsRole = true;

								if($area['id_provinsi'] > 0){
									if($val[15] != $area['id_provinsi']){
										$sttsRole = false;
										$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
									}else{

										if($area['id_kabupaten_kota'] > 0){
											if($val[16] != $area['id_kabupaten_kota']){
												$sttsRole = false;
												$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
											}else{
												if($area['id_kecamatan'] > 0){
													if($val[17] != $area['id_kecamatan']){
														$sttsRole = false;
														$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
													}else{
														if($area['id_kelurahan'] > 0){
															if($val[18] != $area['id_kelurahan']){
																$sttsRole = false;
																$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
															}
														}else{

															//cek kelurahan
															$getkel = $adeQ->select("select count(1) as cek from data_ref_kelurahan where id_kecamatan=".$val[17]." and id=".$val[18]);
															if($getkel[0]['cek'] == 0){
																$sttsRole = false;
																$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
															}

														}
													}
												}else{

													//cek kecamatan
													$getkec = $adeQ->select("select count(1) as cek from data_ref_kecamatan where id_kabupaten_kota=".$val[16]." and id=".$val[17]);
													if($getkec[0]['cek'] == 0){
														$sttsRole = false;
														$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
													}

												}
											}
										}else{
											//cek kab/kota
											$getkabKota = $adeQ->select("select count(1) as cek from data_ref_kabupaten_kota where id_provinsi=".$val[15]." and id=".$val[16]);
											if($getkabKota[0]['cek'] == 0){
												$sttsRole = false;
												$reject[] =  "NIK $nik_ktp Di luar akses role wilayah !!";
											}

										}
									}
								}

								

								

								

								
								$ktp_nama = $val[1];
								$id_kabupaten_kota_ktp_tempat_lahir = $val[2];
								$id_kecamatan_ktp = $val[3];
								$id_kelurahan_ktp = $val[4];
								$ktp_tgl_lahir = gmdate("Y-m-d", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($val[5]));
								$ktp_jenis_kelamin = $val[6];
								$ktp_alamat = str_replace("'", "''", $val[7]);
								$ktp_rt = str_replace("'", "''", $val[8]);
								$ktp_rw = str_replace("'", "''", $val[9]);
								$id_ktp_agama = $val[10];
								$ktp_pekerjaan = str_replace("'", "''", $val[11]);
								$golongan_darah = $val[12];
								$tlp_mobile = $val[13];
								$email = $val[14];
								$id_provinsi_domisili = $val[15];
								$id_kabupaten_kota_domisili = $val[16];
								$id_kecamatan_domisili = $val[17];
								$id_kelurahan_domisili = $val[18];
								$domisili_kodepos = $val[19];
								$domisili_alamat = str_replace("'", "''", $val[20]);
								$domisili_rt = str_replace("'", "''", $val[21]);
								$domisili_rw = str_replace("'", "''", $val[22]);
								

								if($sttsRole){
									$ins = $adeQ->query("insert into data_mst_pemilik_usaha (
										nik_ktp, ktp_nama, id_kabupaten_kota_ktp_tempat_lahir, ktp_tgl_lahir, ktp_jenis_kelamin, id_kelurahan_ktp, id_kecamatan_ktp, ktp_alamat, ktp_rt, ktp_rw, id_ktp_agama, ktp_pekerjaan, golongan_darah, tlp_mobile, email, id_provinsi_domisili, id_kabupaten_kota_domisili, id_kecamatan_domisili, id_kelurahan_domisili, domisili_kodepos, domisili_alamat, domisili_rt, domisili_rw, insert_date, id_user_insert
									) values (
										'$nik_ktp', '$ktp_nama', $id_kabupaten_kota_ktp_tempat_lahir, '$ktp_tgl_lahir', '$ktp_jenis_kelamin', $id_kelurahan_ktp, $id_kecamatan_ktp, '$ktp_alamat', '$ktp_rt', '$ktp_rw', $id_ktp_agama, '$ktp_pekerjaan', $golongan_darah, '$tlp_mobile', '$email', $id_provinsi_domisili, $id_kabupaten_kota_domisili, $id_kecamatan_domisili, $id_kelurahan_domisili, '$domisili_kodepos', '$domisili_alamat', '$domisili_rt', '$domisili_rw', now(), 999
									)");
									if(!$ins){
										$reject[] = $nik_ktp;
									}
								}
								
							}

							

							
						}
						
					}
					$i++;
				}

				$msg = '';
				if(count($reject) > 0){
					$msg = "Data berhasil di upload dengan beberapa data reject";
					$csv = "Data berhasil di upload dengan beberapa data reject yaitu : \n". implode("\n", $reject);
					$rej = 1;
				}else{
					$msg = "Data berhasil di upload.";
					$csv = "" ;
					$rej = 0;
				}

				echo json_encode(["status" => true, "msg" => $msg, "csv" => $csv, "rej" => $rej]);
	
			}else{
				$msgInfo = '';
				foreach($validate as $dt){
					if(!in_array($dt['field'], ['pdf2_upload', 'pdf3_upload', 'pdf4_upload'])){
						if($dt['err'] == "validate"){
							$msgInfo .= "\nData : ".$dt['field'].', '.$dt['msg'];
						}
					}
				}
				echo json_encode(["status" => false, "msg" => "$msgInfo", "validate" => $validate]);
	
			}

		}
	} // close $f
}// close session

?>
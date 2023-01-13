<?php
require "config.php";
require "lib/base/db.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>UMKM</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo $dir ?>assets/img/logo.png" />



	<link type="text/css" rel="stylesheet" href="<?php echo $dir ?>plugins/toater/toastr.min.css" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/css/main.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/spinner/ladda.min.css">
	<!--===============================================================================================-->
</head>

<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="padding-top: 50px;padding-bottom: 10px">
				<div class="col-md-12 text-center" style="padding-bottom: 35px">
					<span class="login100-form-title" style="padding-bottom: 5px;font-size: 35px">
						Register ODS UMKM
					</span>
				</div>


				<form class="login100-form validate-form login" style="width:100%;padding:0 30px" action="javascript:void(0);">
					<span class="login100-form-title" style="padding-bottom: 5px">
						Registrasi
					</span>
					<span class="login50-form-title" style="text-align: center;font-size: 13px;padding-bottom: 10px;width: 100%;display: block;">
						Silakan masukan data diri anda
					</span>

					<div class="wrap-input100 validate-input" data-validate="Email Harus Di Isi">
						<input class="input100 userid" type="email" name="email" placeholder="Masukan Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="NIK KTP Harus Di Isi">
						<input class="input100 pass" type="text" name="nik_ktp" data-mask data-inputmask='"mask": "[9999999999999999]"' placeholder="Masukan NIK KTP">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-credit-card" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Nama Harus Di Isi">
						<input class="input100 userid" type="text" name="ktp_nama" placeholder="Masukan Nama KTP">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Telp Harus Di Isi">
						<input class="input100 userid" type="password" name="password" placeholder="Masukan Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="container-login100-form-btn">
						<button type="button" class="login100-form-btn submit ladda-button" data-color="green" data-style="contract">
							Daftar
						</button>
					</div>


					<div class="text-center p-t-136">

					</div>
				</form>
			</div>
		</div>
	</div>




	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/vendor/jquery/jquery-3.2.1.min.js"></script>

	<script src="<?php echo $dir ?>plugins/spinner/spin.min.js"></script>
	<script src="<?php echo $dir ?>plugins/spinner/ladda.min.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="<?php echo $dir ?>plugins/toater/toastr.min.js"></script>
	<script type="text/javascript" src="<?php echo $dir ?>plugins/dist/js/function.js"></script>

	<script src="<?php echo $dir ?>plugins/login/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo $dir ?>plugins/login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/vendor/tilt/tilt.jquery.min.js"></script>
	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/js/main.js"></script>

	<script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.js"></script>
	<script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.extensions.js"></script>

	<script type="text/javascript">
		var l = Ladda.create(document.querySelector('.submit'));
		$("[data-mask]").inputmask();

		$('.submit').on('click', function() {
			l.start();

			var login = $('.login').serialize();
			$.ajax({
				url: "./lib/ods/registration_act.php?" + login,
				dataType: "json",
				success: function(msg) {
					popup(msg.status, msg.text, '');

					if (msg.status == 'success') {
						$("input").val('');
					}

					l.stop();

				},
				error: function(err) {
					popup('error', 'Error System', '');
					console.log(err);
					l.stop();
				}
			})
		})
	</script>

</body>

</html>
<?php
require "config.php";
require "lib/base/db.php";
require "lib/base/security_login.php";


?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ODS UMKM</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" href="<?php echo $dir ?>assets/img/logo.png" />
  
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/jquery-ui.css">

  <!--CUstom.css-->
  <link type="text/css" rel="stylesheet" href="<?php echo $dir ?>plugins/custom/custom.css" />
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="<?php echo $dir ?>plugins/toater/toastr.min.css" />
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/autocomplate/styles.css">

  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/bootstrap/css/bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/ionicons.min.css">
  <!-- Select style -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/select2/select2.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/iCheck/all.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datepicker/datepicker3.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datatables/dataTables.bootstrap.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- dataTables Select Min Css -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datatables/dataTables.select.min.css">

  <link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/spinner/ladda.min.css">

  <link rel="stylesheet" href="<?php echo $dir ?>plugins/jQuery-Tree-Filter/jquery.treefilter.css">

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo $dir ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="<?php echo $dir ?>plugins/toater/toastr.min.js"></script>
  <script type="text/javascript" src="<?php echo $dir ?>plugins/dist/js/function.js"></script>
  <!-- Autocomplate -->
  <script src="<?php echo $dir ?>plugins/autocomplate/jquery.mockjax.js"></script>
  <script src="<?php echo $dir ?>plugins/autocomplate/jquery.autocomplete.js"></script>

  <script src="<?php echo $dir ?>plugins/chartjs/Chart.min.js"></script>
  <!-- DataTables -->
  <script src="<?php echo $dir ?>plugins/datatables/jquery.dataTables_1_10_20.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/dataTables.bootstrap.min.js"></script>


  <script src="<?php echo $dir ?>plugins/datatables/dataTables.buttons.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/buttons.flash.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/jszip.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/vfs_fonts.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/buttons.html5.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/buttons.print.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/dataTables.select.min.js"></script>

  <script src="<?php echo $dir ?>plugins/spinner/spin.min.js"></script>
  <script src="<?php echo $dir ?>plugins/spinner/ladda.min.js"></script>


  <style>
    #loading-image {
      position: fixed;
      top: 50%;
      width: 25%;
      left: 50%;
      z-index: 999;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
    }

    #loading {
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      position: fixed;
      display: block;
      opacity: 0.7;
      background-color: #ffffff;
      z-index: 9999;
      text-align: center;
    }

    .hide {
      display: none;
    }

    [style*="--aspect-ratio"]> :first-child {
      width: 100%;
    }

    [style*="--aspect-ratio"]>img {
      height: auto;
    }

    @supports (--custom:property) {
      [style*="--aspect-ratio"] {
        position: relative;
      }

      [style*="--aspect-ratio"]::before {
        content: "";
        display: block;
        padding-bottom: calc(100% / (var(--aspect-ratio)));
      }

      [style*="--aspect-ratio"]> :first-child {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
      }
    }
  </style>

</head>

<body class="layout-boxed sidebar-mini skin-black">
  <div id="loading" class="hide">
    <img id="loading-image" src="./assets/img/loading2.gif" alt="Loading..." />
  </div>

  <div class="wrapper">
    <!-- Main content -->
    <section class="content-wrapper" style="margin: 0; padding: 20px">
      <div class="box">
        <div class="box-header with-border text-center">
          <img src="<?php echo $dir ?>assets/img/logo.png" alt="IMG" style="width:100px">
          <br>
          <br>
          <h3 class="box-title" style="line-height:1.4"><b>PENDATAAN AWAL ODS UMKM</b></h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12 containerWrapper">

              <div id="containerPage">
                <div class="text-center" style="font-size: 20px;">
                  Selamat datang di aplikasi ODS UMKM<br>
                  Silakan mengikuti instruksi pengisian data awal untuk melengkapi data ODS UMKM<br>
                  harap siapkan data diri anda dan data usaha anda.

                  <br>
                  <br>
                  <br>
                  <button class="btn btn-success btn-lg mulai">MULAI MENGISI DATA</button>
                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- /.box-footer-->
      </div>
    </section>
  </div>

  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo $dir ?>plugins/dist/js/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.6 -->
  <script src="<?php echo $dir ?>plugins/bootstrap/js/bootstrap.min.js"></script>
  <!-- Input Mask 3.3.6 -->
  <script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.js"></script>
  <script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.extensions.js"></script>

  <!-- Morris.js charts -->
  <script src="<?php echo $dir ?>plugins/dist/js/raphael-min.js"></script>
  <!-- Sparkline -->
  <script src="<?php echo $dir ?>plugins/sparkline/jquery.sparkline.min.js"></script>
  <!-- jvectormap -->
  <script src="<?php echo $dir ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="<?php echo $dir ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="<?php echo $dir ?>plugins/knob/jquery.knob.js"></script>
  <!-- daterangepicker -->
  <script src="<?php echo $dir ?>plugins/dist/js/moment.min.js"></script>
  <script src="<?php echo $dir ?>plugins/daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="<?php echo $dir ?>plugins/datepicker/bootstrap-datepicker.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="<?php echo $dir ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- Slimscroll -->
  <script src="<?php echo $dir ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="<?php echo $dir ?>plugins/fastclick/fastclick.js"></script>
  <!-- bootstrap datepicker -->
  <script src="<?php echo $dir ?>plugins/datepicker/bootstrap-datepicker.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo $dir ?>plugins/dist/js/app.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="<?php echo $dir ?>plugins/iCheck/icheck.min.js"></script>

  <!-- Select JS -->
  <script src="<?php echo $dir ?>plugins/select2/select2.full.min.js"></script>

  <!-- AdminLTE for demo purposes -->
  <script src="<?php echo $dir ?>plugins/dist/js/demo.js"></script>

  <script src="<?php echo $dir ?>plugins/jQuery-Tree-Filter/jquery.treefilter.js"></script>

  <script src="<?php echo $dir ?>plugins/custom/paging_page.js?v=2"></script>

  <script>
    // var paggingPage = new PagingNavigation('#containerPage');

    $(".mulai").on("click", function() {

      HtmlLoad('./lib/umkm/form_navigation_structure_pemilik_usaha.php?f=27', 'Pemilik Usaha');

    });

    function HtmlLoad(url, menux) {

      $("#loading").removeClass("hide");

      var linksx = '<li><a href="./"><i class="fa fa-dashboard"></i> Home</a> &#187; ' + menux + '</li>';
      //alert(linksx);
      if (typeof ws !== 'undefined') {
        ws.close();
      }
      if (typeof $('.ui-jqdialog') !== 'undefined') {
        $('body').children('.ui-jqdialog').remove();
      }
      $(".containerWrapper").html(linksx);
      $(".containerWrapper").empty();
      $(".containerWrapper").load(url);
      $("#loading").addClass("hide");
    }
  </script>



</body>

</html>
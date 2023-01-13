<?php
require "config.php";
require "lib/base/db.php";
require "lib/ods/security_register.php";

$_SESSION['token'] = isset($_GET['token']) ? $_GET['token'] : null;

?> 


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>UMKM</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <!--CUstom.css-->
  <link type="text/css" rel="stylesheet" href="<?php echo $dir?>plugins/custom/custom.css"/>
    <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="<?php echo $dir?>plugins/toater/toastr.min.css"/>
  <link rel="stylesheet" href="<?php echo $dir?>plugins/autocomplate/styles.css">
  
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/bootstrap/css/bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/dist/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/dist/css/ionicons.min.css">
  <!-- Select style -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/select2/select2.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/iCheck/all.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/datepicker/datepicker3.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/datatables/dataTables.bootstrap.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- dataTables Select Min Css -->
  <link rel="stylesheet" href="<?php echo $dir?>plugins/datatables/dataTables.select.min.css">
  
  <link rel="stylesheet" type="text/css" href="<?php echo $dir?>plugins/spinner/ladda.min.css">

  <link rel="stylesheet" href="<?php echo $dir?>plugins/jQuery-Tree-Filter/jquery.treefilter.css">

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo $dir?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="<?php echo $dir?>plugins/toater/toastr.min.js"></script>
  <script type="text/javascript" src="<?php echo $dir?>plugins/dist/js/function.js"></script>
  <!-- Autocomplate -->
  <script src="<?php echo $dir?>plugins/autocomplate/jquery.mockjax.js"></script>
  <script src="<?php echo $dir?>plugins/autocomplate/jquery.autocomplete.js"></script>
  
  <script src="<?php echo $dir?>plugins/chartjs/Chart.min.js"></script>
  <!-- DataTables -->
  <script src="<?php echo $dir?>plugins/datatables/jquery.dataTables_1_10_20.min.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/dataTables.bootstrap.min.js"></script>
  
  
  <script src="<?php echo $dir?>plugins/datatables/dataTables.buttons.min.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/buttons.flash.min.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/jszip.min.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/vfs_fonts.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/buttons.html5.min.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/buttons.print.min.js"></script>
  <script src="<?php echo $dir?>plugins/datatables/dataTables.select.min.js"></script>

  <script src="<?php echo $dir?>plugins/spinner/spin.min.js"></script>
  <script src="<?php echo $dir?>plugins/spinner/ladda.min.js"></script>
  
  
</head>
<body class="hold-transition skin-black-light layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
     

        <div class="container">
            <div class="navbar-header text-center" style="width:100%">
              <a href="#" style="width:100%" class="navbar-brand"> FORM REGISTRASI ONLINE UMKM</a>
            </div>

          
          <!-- /.container-fluid -->
        </div>
    </nav>
  </header>



    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="mainContent">
    
  </div>

<!-- jQuery UI 1.11.4 -->
<script src="<?php echo $dir?>plugins/dist/js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $dir?>plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- Input Mask 3.3.6 -->
<script src="<?php echo $dir?>plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo $dir?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo $dir?>plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- Morris.js charts -->
<script src="<?php echo $dir?>plugins/dist/js/raphael-min.js"></script>
<!-- Sparkline -->
<script src="<?php echo $dir?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo $dir?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo $dir?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo $dir?>plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="<?php echo $dir?>plugins/dist/js/moment.min.js"></script>
<script src="<?php echo $dir?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo $dir?>plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $dir?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo $dir?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $dir?>plugins/fastclick/fastclick.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo $dir?>plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $dir?>plugins/dist/js/app.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo $dir?>plugins/iCheck/icheck.min.js"></script>

<!-- Select JS -->
<script src="<?php echo $dir?>plugins/select2/select2.full.min.js"></script>
  
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $dir?>plugins/dist/js/demo.js"></script>

<script src="<?php echo $dir?>plugins/jQuery-Tree-Filter/jquery.treefilter.js"></script>


<script>

function HtmlPush(url){  
  alert("2");
  var html = [];                        
   html.push('<div class="tabIframeWrapper">');
   html.push('<iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe>');
   html.push('</div>');
   var stringx='<div class="tabIframeWrapper"><iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe></div>';
   var tinggi=window.innerHeight - ($("#main-header").innerHeight()+$("#main-footer").innerHeight());
   $("#mainContent").empty();
   $("#mainContent").html(stringx); 
   $("#mainContent").find("iframe").height($("#mainContent").innerHeight()- 20);
   $("#mainContent").find("iframe").width('100%');
   //alert($("#content-wrapper").innerHeight());
 }
 
  function HtmlPush(url,title,navigate){  
   
  var html = [];        
  
   html.push('<div class="tabIframeWrapper">');
   html.push('<iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe>');
   html.push('</div>');
   var stringx='<div class="box box-default box-success"><div class="box-header with-border"><h3 class="box-title">'+title+'</h3></div><div class="box-body"><iframe class="iframetab" style="bottom:0;top:0;left:0;right:0;margin-top:0px;margin-bottom:0px;margin-right:0px;margin-left:0px;" frameborder = "0" src="' + url + '">Load Failed?</iframe></div></div>';
   var tinggi=window.innerHeight - ($("#main-header").innerHeight()+$("#main-footer").innerHeight());
   //alert(tinggi);
   $("#mainTitle").val(title);
   $("#mainNavigate").val(navigate);
   
   $("#mainContent").empty();
   $("#mainContent").html(stringx); 
   $("#mainContent").find("iframe").height(tinggi);
   $("#mainContent").find("iframe").width('100%'); 
 }
 
 function HtmlLoad(url,title,navigate){      
  $("#mainTitle").val(title);
  $("#mainNavigate").val(navigate);
  $("#mainContent").empty();
  $("#mainContent").load(url); 
 }
 
 function HtmlLoad(url,menux){       
  var linksx='<li><a href="./"><i class="fa fa-dashboard"></i> Home</a> &#187; '+menux+'</li>';
  //alert(linksx);
  if (typeof ws !== 'undefined')  { ws.close();}
  if (typeof $('.ui-jqdialog')!== 'undefined')  {  
    $('body').children('.ui-jqdialog').remove(); 
  }
  $("#mainNavigate").html(linksx);
  $("#mainContent").empty();
  $("#mainContent").html('<div class="overlay" style="font-size: 20px;text-align:center;margin-top: 20%;"><i class="fa fa-refresh fa-spin"></i>  Mohon Tunggu</div>');
  $("#mainContent").load(url); 
 }


$("[data-mask]").inputmask();
HtmlLoad('<?php echo $dir ?>lib/ods/registrasi_online.php?f=47,48','Audit Trail');


</script>



</body>
</html>
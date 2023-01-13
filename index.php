<?php
require "config.php";
require "lib/base/db.php";
require "lib/base/security_login.php";
require "lib/umkm/checkfirstlogin.php";

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
  <link type="text/css" href="<?php echo $dir ?>plugins/datatables/css/dataTables.checkboxes.css" rel="stylesheet" />
  <script type="text/javascript" src="<?php echo $dir ?>plugins/datatables/js/dataTables.checkboxes.min.js"></script>


  <script src="<?php echo $dir ?>plugins/spinner/spin.min.js"></script>
  <script src="<?php echo $dir ?>plugins/spinner/ladda.min.js"></script>


  <style>

  th,
    td {
      white-space: nowrap;
    }

    div.dataTables_wrapper {
      margin: 0 auto;
    }

    div.container {
      width: 80%;
    }
	

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

<body class="hold-transition skin-black-light sidebar-mini fixed sidebar-mini-expand-feature">
  <div id="loading" class="hide">
    <img id="loading-image" src="./assets/img/loading2.gif" alt="Loading..." />
  </div>

  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>UMKM</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>ODS</b> UMKM</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                  <li>
                    <a href="#">
                      <div class="pull-left">
                        <img src="<?php echo $dir ?>plugins/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  
                </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          -->

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo $dir ?>assets/img/icon_user.png" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['username'] ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo $dir ?>assets/img/icon_user.png" class="img-circle" alt="User Image">

                  <p>
                    <?php echo $_SESSION['username'] ?>
                    <small></small>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" onclick="HtmlLoad('./lib/base/config_person_user.php','Config Person User');" class="btn btn-default btn-flat"> Pengaturan</a>
                  </div>
                  <div class="pull-right">
                    <a href="#" class="btn btn-default btn-flat logout"> Keluar</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>


    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less 
   <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="searchmenu" id="searchmenu" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!--div class="header" align="center" style="border=1"><font color="#FFFFFF">MAIN NAVIGATION</font></div-->
      <ul class="sidebar-menu" style="font-size: 12px;">
        <li class="header">
          <font><b>Main Menuss</b></font><button type="button" class="btn btn-box-tool pull-right-container" data-widget="refresh" id="refreshmenu" title="Refresh Menu"><i id="refreshicon" class="fa fa-refresh pull-right"></i></button>
        </li>
      </ul>
      <section class="sidebar">
        <!-- search form -->
        <ul class="sidebar-menu" id="sidebarmenu" style="font-size: 12px; font-weight: bold;" data-widget="tree"></ul>
      </section>

      <!-- /.sidebar -->
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="mainContent">

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


    <script>
      function Iterate(data) {
        var i = 0;
        var menus = "";
        jQuery.each(data, function(index, value) {
          var tooltipDesc = value.description == '' ? '' : 'data-toggle="tooltip" data-placement="right" title="' + value.description + '"';

          if (typeof value == 'object') {
            if (value.parent == '0') {
              $parent = $('<li class="treeview" ><a href="#"><i class="fa ' + value.icon + '"></i><span>' + value.menu + '</span><span class="pull-right-container"><i class="fa fa-angle-double-left pull-right-container"></i></span></a><ul class="treeview-menu" data-widget="tree" id="idmenu' + value.idmenus + '"></ul></li>');
              $("#sidebarmenu").append($parent);
            } else {
              if (value.links == '') {
                //alert(value.menu); 
                /*  $child = $('<li><a href="#"><i class="fa '+value.icon+'"></i><span>'+value.menu+'</span></a><ul class="treeview-menu" data-widget="tree" id="idmenu'+value.idmenus+'"></ul></li>');
                 */
                $child = $('<li class="treeview" ><a href="#" ' + tooltipDesc + '><i class="fa ' + value.icon + '"></i><span>' + value.menu + '</span><span class="pull-right-container"><i class="fa fa-angle-double-left pull-right-container"></i></span></a><ul class="treeview-menu" data-widget="tree" id="idmenu' + value.idmenus + '"></ul></li>');

              } else {
                if (value.withframe == '1') {
                  $child = $('<li><a href="#" ' + tooltipDesc + ' onclick="HtmlPush(\'' + value.links + '\',\'' + value.menu + '\');"><i class="fa ' + value.icon + '"></i>' + value.menu + '</a></li>');
                } else {
                  $child = $('<li><a href="#" ' + tooltipDesc + ' onclick="HtmlLoad(\'' + value.links + '\',\'' + value.menu + '\');"><i class="fa ' + value.icon + '"></i>' + value.menu + '</a></li>');
                }
              }
              $("#idmenu" + value.parent).append($child);
            }
          }

        });
        //$.AdminLTE.tree('.sidebar');
      };



      function HtmlPush(url) {
        alert("2");
        var html = [];
        html.push('<div class="tabIframeWrapper">');
        html.push('<iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe>');
        html.push('</div>');
        var stringx = '<div class="tabIframeWrapper"><iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe></div>';
        var tinggi = window.innerHeight - ($("#main-header").innerHeight() + $("#main-footer").innerHeight());
        $("#mainContent").empty();
        $("#mainContent").html(stringx);
        $("#mainContent").find("iframe").height($("#mainContent").innerHeight() - 20);
        $("#mainContent").find("iframe").width('100%');
        //alert($("#content-wrapper").innerHeight());
      }

      function HtmlPush(url, title, navigate) {

        var html = [];

        html.push('<div class="tabIframeWrapper">');
        html.push('<iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe>');
        html.push('</div>');
        var stringx = '<div class="box box-default box-success"><div class="box-header with-border"><h3 class="box-title">' + title + '</h3></div><div class="box-body"><iframe class="iframetab" style="bottom:0;top:0;left:0;right:0;margin-top:0px;margin-bottom:0px;margin-right:0px;margin-left:0px;" frameborder = "0" src="' + url + '">Load Failed?</iframe></div></div>';
        var tinggi = window.innerHeight - ($("#main-header").innerHeight() + $("#main-footer").innerHeight());
        //alert(tinggi);
        $("#mainTitle").val(title);
        $("#mainNavigate").val(navigate);

        $("#mainContent").empty();
        $("#mainContent").html(stringx);
        $("#mainContent").find("iframe").height(tinggi);
        $("#mainContent").find("iframe").width('100%');
      }

      function HtmlLoad(url, title, navigate) {
        $("#mainTitle").val(title);
        $("#mainNavigate").val(navigate);
        $("#mainContent").empty();
        $("#mainContent").load(url);
      }

      function HtmlLoad(url, menux) {
        var linksx = '<li><a href="./"><i class="fa fa-dashboard"></i> Home</a> &#187; ' + menux + '</li>';
        //alert(linksx);
        if (typeof ws !== 'undefined') {
          ws.close();
        }
        if (typeof $('.ui-jqdialog') !== 'undefined') {
          $('body').children('.ui-jqdialog').remove();
        }
        $("#mainNavigate").html(linksx);
        $("#mainContent").empty();
        $("#mainContent").html('<div class="overlay" style="font-size: 20px;text-align:center;margin-top: 20%;"><i class="fa fa-refresh fa-spin"></i>  Mohon Tunggu</div>');
        $("#mainContent").load(url);
      }


      $('.logout').on('click', function() {
        $.ajax({
          url: "./lib/base/logout.php",
          dataType: "json",
          success: function(msg) {
            window.location.href = msg;
          }
        })
      })


      $('#refreshmenu').on("click", function() {
        $('#sidebarmenu').find('.treeview').empty();
        //$('#refreshicon').attr('class','fa fa-spinner'); 

        $.ajax({
          method: "POST",
          url: "./lib/base/load_menu.php",
          success: function(msg) {
            var jsonstr = msg;
            //console.log(JSON.parse(jsonstr));
            Iterate(JSON.parse(jsonstr));

            $('[data-toggle="tooltip"]').on('click', function() {
              $(this).tooltip('hide')
            });

          }
        });
      });
      $('#refreshmenu').trigger("click");

      var tree = new treefilter($("#sidebarmenu"), {
        searcher: $("input#searchmenu"),
        expanded: true,
        offsetLeft: 20,
        multiselect: true


      });

      $("[data-mask]").inputmask();
      HtmlLoad('welcome.php', 'Audit Trail');


      function imageInit(cssimg, cssInptFile, cssinfo) {
        cssInptFile.css('display', 'none');
        cssimg.on("click", function() {
          cssInptFile.click();
          var img = $(this);
          cssInptFile.on("change", function(e) {
            var file = e.originalEvent.srcElement.files[0];

            //security
            console.log(file);
            if (
              file.type.substring(0, 4) == 'text' || file.type == ''
            ) {
              popup('error', 'File Not Permission !!!', '');
              cssInptFile.val('');
            } else {
              var reader = new FileReader();
              reader.onloadend = function() {
                if (file.type == 'application/pdf') {
                  img.attr("src", 'assets/img/pdf_icon2.png');
                } else if (file.type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                  img.attr("src", 'assets/img/excel_icon.png');
                } else {
                  img.attr("src", reader.result);
                }

                cssinfo.html(file.name);
              }
            }


            reader.readAsDataURL(file);
          });
        });
      }
    </script>



</body>

</html>
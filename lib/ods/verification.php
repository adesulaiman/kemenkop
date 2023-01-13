<?php

require "../../config.php";
require "../base/db.php";

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  $qCekReg = $adeQ->select($adeQ->prepare(
    "
        select * from core_user
        where md5(email || '-' || userid) = %s
      ",
    $token
  ));

  if (count($qCekReg) > 0) {
    $qUpdValidasiMail = $adeQ->query($adeQ->prepare("
        update core_user set isactive = 1
        where md5(email || '-' || userid) = %s
      ", $token));

      if($qUpdValidasiMail){
        
        echo "
        <script>
        if (confirm('Akun Anda Sudah Terverfikasi, Silakan login !!')) {
            window.location.href = '".$dir."login.php';
        }
        </script>
        ";
      }else{
        echo "<h2 style='width:100%;text-align:center'>Database Error, please contact Administrator !!</h2>";
      }
  }else{
    echo "<h2 style='width:100%;text-align:center'>Error Token !!</h2>";
  }
}

?>

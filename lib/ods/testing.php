<?php

require "../../config.php";
//require "../base/security_login.php";
require "../base/db.php";
require "functions.php";

$cek = get_uniq_by_column('data_usaha', 'nik_ktp', '123', $adeQ);

$v='67281626186736271';

$value = $adeQ->select($adeQ->prepare("select * from data_usaha where id=%s", $v));

print_r($value);
echo $cek;
?>
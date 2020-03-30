<?php 

$olddata = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
$tempdata = explode('!//#',$olddata);
// var_dump($tempdata);

$loadNewDataQuery = $connect->prepare("SELECT id_uzytkownika,id_rolu,id_statusu,id_profilu FROM uzytkownicy WHERE id_uzytkownika = ?");
$loadNewDataQuery->bind_param('i',$tempdata[0]);
$loadNewDataQuery->execute();
$loadNewDataResult = $loadNewDataQuery->get_result();
$newdata = mysqli_fetch_assoc($loadNewDataResult);

$newdata = implode('!//#',$newdata);
// var_dump($newdata);
$newdata = openssl_encrypt($newdata,'rc4-hmac-md5','ptaki_lataja_kluczem');
$_SESSION['user'] = $newdata;
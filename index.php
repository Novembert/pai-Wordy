<?php
  session_start();
  if(!isset($_SESSION['user'])){
    $_SESSION['user'] = null;
    header('Location: login.php');
  }else if(isset($_SESSION['user'])){
    $data = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
    $data = explode('!//#',$data);

    if($data[2] == 1){
      if($data[1] == 1) {
        header('Location: student');
      }else if($data[1] == 2) {
        header('Location: teacher');
      }else if($data[1] == 3){
        header('Location: admin');
      }else {
        // nieobsluzony przypadek (jeszcze nwm czy zostawie)
      }
    }
  }
?>

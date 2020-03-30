<?php
    $connect = mysqli_connect("localhost", "root", "", "wiai2");
    mysqli_set_charset($connect, "utf8");
  
    session_start();
    if(!isset($_SESSION['user'])){
      header('Location: ../login.php');
    }else {
      
      require('../scripts/reload_user.php');
  
      $data = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
      $data = explode('!//#',$data);

      require('../scripts/load_profile.php');
  
      if($data[2] == 1){
        if($data[1] != 2) {
          if($data[1] == 1) {
            header('Location: ../student');
          }else {
            // nieobsluzony przypadek (jeszcze nwm czy zostawie)
          }
        }
        if(!$data[3]){
          header('Location: ./add_profile.php');
        }
      }else if($data[2] == 2){
        // wywołaj jak konto nieaktywne
      }else if($data[2] == 3){
        // wywolaj jak konto usuniete
      }else {
        // nieobsluzony przypadek (jeszcze nwm czy zostawie)
      }
    }
?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wordy | Panel nauczyciela </title>
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=latin-ext"
      rel="stylesheet"
    />
    <script
      src="https://kit.fontawesome.com/c5d4e6fb7f.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="/Wordy/css/style.css" />
  </head>
  <body>
    <?php include('../nav.php') ?>
    <?php include('./dashboard/teacher-dashboard.php')?>

  </body>
</html>

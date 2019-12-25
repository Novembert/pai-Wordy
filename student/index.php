<?php
  session_start();
  if(!isset($_SESSION['user'])){
    header('Location: ../login.php');
  }else {
    $data = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
    $data = explode('!//#',$data);

    if($data[2] == 1){
      if($data[1] != 1) {
        if($data[1] == 2) {
          header('Location: ../teacher');
        }else {
          // nieobsluzony przypadek (jeszcze nwm czy zostawie)
        }
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
    <title>Wordy | Panel ucznia</title>
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
    <?php include('./dashboard/student-dashboard.php')?>

  </body>
</html>

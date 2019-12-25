<?php
  session_start();
  if(isset($_GET['logout']) && isset($_SESSION['user'])){
    $_SESSION['user'] = null;
  }else if(isset($_GET['logout'])) {
    header('Location: login.php');
  }else if(isset($_SESSION['user'])){
    $data = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
    $data = explode('!//#',$data);

    if($data[2] == 1){
        if($data[1] == 1) {
          header('Location: student');
        }else if($data[1] == 2) {
          header('Location: teacher');
        }else {
          // nieobsluzony przypadek (jeszcze nwm czy zostawie)
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
    <title>Wordy | Logowanie</title>
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=latin-ext"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./css/style.css" />
  </head>
  <body id="intro">
    <?php
      $connect = mysqli_connect("localhost", "root", "", "wiai2");
      mysqli_set_charset($connect, "utf8");

      if(isset($_POST['btn1'])){
        $query = $connect->prepare("SELECT id_uzytkownika,id_rolu,id_statusu,id_profilu FROM uzytkownicy WHERE email = ? AND haslo = ?");
        $query->bind_param('ss',$_POST['email'],$_POST['password']);
        $query->execute();
        $result = $query->get_result();
        if(!$result) {
          die('Invalid query: ' . mysqli_error($connect));
        } else if(mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
          $row2 = $row;
          $row = implode('!//#',$row);
          $row = openssl_encrypt($row,'rc4-hmac-md5','ptaki_lataja_kluczem');
          $_SESSION['user'] = $row;
          if($row2['id_statusu'] == 1){
            if($row2['id_rolu'] == 1) {
              header('Location: student');
            }else if($row2['id_rolu'] == 2){
              header('Location: teacher');
            }
          }else if($row2['id_statusu'] == 2){
            // wywołaj jak konto nieaktywne
          }else if($row2['id_statusu'] == 3){
            // wywolaj jak konto usuniete
          }else {
            // nieobsluzony przypadek (jeszcze nwm czy zostawie)
          }
        }else {
          // wywołaj jak nie bedzie dobrego loginu
        }
      }
      mysqli_close($connect);
    ?>
    <section id="central_section">
      <h1>Logowanie</h1>
      <form method="POST">
        <div id="email_div" class="input_container">
          <input type="email" name="email" id="email_input" />
        </div>
        <div id="password_div" class="input_container">
          <input type="password" name="password" id="password_input" />
        </div>
        <input type="submit" name="btn1" value="Prześlij" />
      </form>
      <p>Nie masz konta? <a href="register.php">Zarejestruj się!</a></p>
    </section>
    <script src="js/login_form_animations.js" charset="utf-8"></script>
  </body>
</html>

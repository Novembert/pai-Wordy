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

    if($data[2] == 1){
      if($data[1] != 1) {
        if($data[1] == 2) {
          header('Location: ../teacher');
        }else if($data[1] == 3){
          header('Location: ../admin');
        }else {
          // nieobsluzony przypadek (jeszcze nwm czy zostawie)
        }
        
      }
      if($data[3]){
        header('Location: ./index.php');
      }else {

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
    <title>Wordy | Dodawanie profilu ucznia</title>
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
  <body id="intro">
    <?php
      if(isset($_POST['btn1'])){
        if(empty($_POST['imie']) || 
          empty($_POST['nazwisko']) || 
          empty($_POST['id_klasy'])
        ){
          ?>
            <script type="text/javascript">
              let alertBox = document.createElement('div');
              alertBox.classList.add('alerts-box');
              document.body.appendChild(alertBox);

            </script>
          <?php
          if(empty($_POST['imie'])) {
            ?>
              <script type="text/javascript">
                let alert = document.createElement('div');
                alert.classList.add('empty_alert');
                alert.textContent = "Proszę wypełnić pole 'Imię'";
                alertBox.appendChild(alert);
                window.setInterval(()=>{alert.style.display="none"},2500)
              </script>
            <?php
          }
          if(empty($_POST['nazwisko'])) {
            ?>
              <script type="text/javascript">
                let alert2 = document.createElement('div');
                alert2.classList.add('empty_alert');
                alert2.textContent = "Proszę wypełnić pole 'Nazwisko'";
                alertBox.appendChild(alert2);
                window.setInterval(()=>{alert2.style.display="none"},2500)
              </script>
            <?php
          }
          if(empty($_POST['id_klasy'])) {
            ?>
              <script type="text/javascript">
                let alert3 = document.createElement('div');
                alert3.classList.add('empty_alert');
                alert3.textContent = "Proszę wypełnić pole 'Wybór klasy'";
                alertBox.appendChild(alert3);
                window.setInterval(()=>{alert3.style.display="none"},2500)
              </script>
            <?php
          }
        } else {
          $add_profile_query = $connect->prepare("
            INSERT INTO `profile` (imie, nazwisko, opis, id_klasy) VALUES (?,?,?,?)
          ");
          $add_profile_query->bind_param('sssi',
            $_POST['imie'],
            $_POST['nazwisko'],
            $_POST['opis'],
            $_POST['id_klasy'],
          );
          $add_profile_query->execute();
          $add_profile_result = $add_profile_query->get_result();

          $add_profileid_to_user_query = $connect->prepare("
            UPDATE uzytkownicy SET id_profilu=? WHERE id_uzytkownika=?
          ");
          $add_profileid_to_user_query->bind_param('ii',intval($connect->insert_id),intval($data[0]));
          $data[3] = intval($connect->insert_id);
          $add_profileid_to_user_query->execute();

          $data = implode('!//#',$data);
          $data = openssl_encrypt($data,'rc4-hmac-md5','ptaki_lataja_kluczem');
          $_SESSION['user'] = $data;

          Header('Location: '.$_SERVER['PHP_SELF']);
        }
      }else {
      }
    ?>

    <section id="central_section">
      <h1>Dodawanie profilu</h1>
      <form method="POST">
        <div id="imie_div" class="input_container">
          <input type="text" name="imie" id="imie_input" />
        </div>
        <div id="nazwisko_div" class="input_container">
          <input type="text" name="nazwisko" id="nazwisko_input" />
        </div>
        <div id="opis_div" class="input_container">
          <textarea name="opis" id="opis_input"></textarea>
        </div>
        <div id="id_klasy_div" class="input_container">
        <select name="id_klasy" id="id_klasy_input" class="class_input">
            <option></option>
            <?php 
              $classes_query = $connect->prepare("
                SELECT id_klasy, nazwa_symbolu_klasy
                FROM klasy as a INNER JOIN symbole_klas ON a.id_symbolu_klasy = symbole_klas.id_symbolu_klasy
              ");
              $classes_query->execute();
              $classes_result = $classes_query->get_result();
              while($row = mysqli_fetch_assoc($classes_result)){
                echo "<option value='$row[id_klasy]'>$row[nazwa_symbolu_klasy]</option>";
              }
            ?>
          </select>
        </div>
        <input type="submit" name="btn1" value="Prześlij" />
      </form>
    </section>
    <script src="../js/add_profile_form_animations.js" charset="utf-8"></script>
  </body>
</html>

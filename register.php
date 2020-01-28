<?php
  session_start();
  if(isset($_SESSION['user'])){
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
    }
  }
?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wordy | Rejestracja</title>
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
      if(isset($_POST['btn1']) && isset($_POST['email']) && isset($_POST['email2']) && isset($_POST['password']) && $_POST['role'] && ($_POST['email'] == $_POST['email2'])) {
        if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['email2'])){
          ?>
            <script type="text/javascript">
              let alertBox = document.createElement('div');
              alertBox.classList.add('alerts-box');
              document.body.appendChild(alertBox);

            </script>
          <?php
          if(empty($_POST['email'])) {
            ?>
              <!-- wypelnij email -->
              <script type="text/javascript">
                let alert = document.createElement('div');
                alert.classList.add('empty_alert');
                alert.textContent = "Proszę wypełnić pole 'Email'";
                alertBox.appendChild(alert);
                window.setInterval(()=>{alert.style.display="none"},2500)
              </script>
            <?php
          }
          if (empty($_POST['email2'])) {
            ?>
              <!-- wypelnij email2 -->
              <script type="text/javascript">
                let alert2 = document.createElement('div');
                alert2.classList.add('empty_alert');
                alert2.textContent = "Proszę wypełnić pole 'Potwierdź Email'";
                alertBox.appendChild(alert2);
                window.setInterval(()=>{alert2.style.display="none"},2500)
              </script>
            <?php
          }
          if (empty($_POST['password'])) {
            ?>
              <!-- wypelnij haslo -->
              <script type="text/javascript">
                let alert3 = document.createElement('div');
                alert3.classList.add('empty_alert');
                alert3.textContent = "Proszę wypełnić pole 'Hasło'";
                alertBox.appendChild(alert3);
                window.setInterval(()=>{alert3.style.display="none"},2500)
              </script>
            <?php
          }
        }else {
          // sprawdzanie czy uzytkownik juz istnieje
          $query = $connect->prepare("SELECT email FROM uzytkownicy WHERE id_statusu = 1 OR id_statusu = 2");
          $query->execute();
          $result = $query->get_result();
          $emails = [];
          while($row = mysqli_fetch_assoc($result)){
            array_push($emails,$row['email']);
          }

          $duplicate = false;
          foreach ($emails as $email) {
            if($email == $_POST['email']){
              $duplicate = true;
            }
          }

          if($duplicate){
            ?>
              <script type="text/javascript">
                let alertBox = document.createElement('div');
                alertBox.classList.add('alerts-box');
                document.body.appendChild(alertBox);
                let alert4 = document.createElement('div');
                alert4.classList.add('yellow_alert');
                alert4.textContent = "To konto już istnieje!";
                alertBox.appendChild(alert4);
                window.setInterval(()=>{alert4.style.display="none"},2500)
              </script>
            <?php
          }else {
            $query = $connect->prepare("INSERT INTO uzytkownicy (email,haslo,id_rolu,id_statusu) VALUES (?,?,?,1)");
            $password = $_POST['password'];
            $password = password_hash($password, PASSWORD_ARGON2ID);

            $query->bind_param('ssd',$_POST['email'],$password,$_POST['role']);
            $query->execute();

            // zalogowanie
            $query = $connect->prepare("SELECT id_uzytkownika,id_rolu,id_statusu,id_profilu FROM uzytkownicy WHERE email = ?");
            $query->bind_param('s',$_POST['email']);
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
                ?>
                  <script type="text/javascript">
                    let alertBox = document.createElement('div');
                    alertBox.classList.add('alerts-box');
                    document.body.appendChild(alertBox);
                    let alert5 = document.createElement('div');
                    alert5.classList.add('yellow_alert');
                    alert5.textContent = "Twoje konto jest nieaktywne!";
                    alertBox.appendChild(alert5);
                    window.setInterval(()=>{alert5.style.display="none"},2500)
                  </script>
                <?php
              }else if($row2['id_statusu'] == 3){
                ?>
                  <script type="text/javascript">
                    let alertBox = document.createElement('div');
                    alertBox.classList.add('alerts-box');
                    document.body.appendChild(alertBox);
                    let alert6 = document.createElement('div');
                    alert6.classList.add('yellow_alert');
                    alert6.textContent = "To konto zostało usunięte!";
                    alertBox.appendChild(alert6);
                    window.setInterval(()=>{alert6.style.display="none"},2500)
                  </script>
                <?php
              }else {
                // nieobsluzony przypadek (jeszcze nwm czy zostawie)
              }
            }
          }
        }
      }
      mysqli_close($connect);
    ?>
    <section id="central_section">
      <h1>Rejestruj</h1>
      <form method="POST">
        <div id="email_div" class="input_container">
          <input type="email" name="email" id="email_input" />
        </div>

        <div id="email_conf_div" class="input_container">
          <input type="email" name="email2" id="email_conf_input" />
        </div>

        <div id="password_div" class="input_container">
          <input type="password" name="password" id="password_input" />
        </div>

        <div id="role_div" class="input_container">
          <select class="role_input" name="role">
            <option value="1">Uczeń</option>
            <option value="2">Nauczyciel</option>
          </select>
        </div>

        <input type="submit" name="btn1" value="Prześlij" />
      </form>
      <p>Masz już konto? <a href="login.php">Zaloguj się!</a></p>
    </section>
    <script src="js/register_form_animations.js" charset="utf-8"></script>
  </body>
</html>

<?php
  require_once("./autologin.php")
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
        if(empty($_POST['email']) || empty($_POST['password'])){
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
          $query = $connect->prepare("SELECT haslo FROM uzytkownicy WHERE email = ?");
          $query->bind_param('s',$_POST['email']);
          $query->execute();
          $result = $query->get_result();
          if(!$result) {
            die('Invalid query: ' . mysqli_error($connect));
          }else if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            if(password_verify($_POST['password'],$row['haslo'])){
              $query = $connect->prepare("SELECT id_uzytkownika,id_rolu,id_statusu,id_profilu FROM uzytkownicy WHERE email = ?");
              $query->bind_param('s',$_POST['email']);
              $query->execute();
              $result = $query->get_result();
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
            }else {
              ?>
                <script type="text/javascript">
                  console.log('dupa')
                  let alertBox = document.createElement('div');
                  alertBox.classList.add('alerts-box');
                  document.body.appendChild(alertBox);
                  let alert7 = document.createElement('div');
                  alert7.classList.add('yellow_alert');
                  alert7.textContent = "Nieprawidłowe dane logowania!";
                  alertBox.appendChild(alert7);
                  window.setInterval(()=>{alert7.style.display="none"},2500)
                </script>
              <?php
            }
          }else if(mysqli_num_rows($result) == 0){
            ?>
              <script type="text/javascript">
                let alertBox = document.createElement('div');
                alertBox.classList.add('alerts-box');
                document.body.appendChild(alertBox);
                let alert7 = document.createElement('div');
                alert7.classList.add('yellow_alert');
                alert7.textContent = "Nieprawidłowe dane logowania!";
                alertBox.appendChild(alert7);
                window.setInterval(()=>{alert7.style.display="none"},2500)
              </script>
            <?php
          }
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

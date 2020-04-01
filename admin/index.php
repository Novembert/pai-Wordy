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
      if($data[1] != 3) {
        if($data[1] == 2) {
          header('Location: ../teacher');
        }else if($data[1] == 1) {
          header('Location: ../student');
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
    <title>Wordy | Panel administratora</title>
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=latin-ext"
      rel="stylesheet"
    />
    <script
      src="https://kit.fontawesome.com/c5d4e6fb7f.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="/Wordy/css/style.css" />
    <style>
      body {
        margin-left: 0 !important;
      }

      form {
        padding: 10px 20px;
        color:white;
      }
    
      input[type="submit"],button {
        position: relative;
        padding: 5px 8px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        border: none;
        width: 100%;
        margin: 20px 0 10px;
        border-radius: 3px;
        height: 35px;
        font-size: 16px;
        font-family: 'Roboto', sans-serif;
        background-color: #fff;
        display: block;
        cursor:pointer;
        width: 100px;
        margin: 5px auto 10px;
        background-color: #fff;
        color: #5cbbff;
        font-weight: bold;
      }

      form {
        max-width:380px;
        margin: 0 auto;
      }

      table {
        position: relative;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        border: none;
        margin: 20px 0 10px;
        border-radius: 3px;
        height: 35px;
        font-size: 16px;
        font-family: 'Roboto', sans-serif;
        background-color: #fff;
        margin: 5px auto 10px;
        background-color: #fff;
        max-width: 1500px;
      }

      input[type="text"]{
        position: relative;
        padding: 5px 8px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.25);
        border: none;
        width: 100%;
        margin: 20px 0;
        border-radius: 3px;
        height: 35px;
        font-size: 16px;
        font-family: 'Roboto', sans-serif;
        background-color: #fff;
      }

      table th, td {
        padding: 5px 15px;
        text-align:left;
      }

      table th {
        color: #5cbbff;
        font-weight: bold;
      }
    </style>
  </head>
  <body id="intro">
    <h1>Panel administratora</h1>
    <h2>Użytkownicy</h2>

    <?php
    
    if(isset($_GET['user_id'])){
      $id = $_GET['user_id'];
      $getUserData = $connect->prepare("
        SELECT 
        `statusy`.id_statusu AS 'status_id',
        `role`.id_roli AS 'rola_id'
        FROM `uzytkownicy`
        INNER JOIN `statusy` ON `uzytkownicy`.id_statusu = `statusy`.id_statusu
        INNER JOIN `role` ON `uzytkownicy`.id_rolu = `role`.id_roli
      WHERE `uzytkownicy`.id_uzytkownika = ?
      ");
      $getUserData->bind_param('i',$id);
      $getUserData->execute();
      $getUserDataResult= $getUserData->get_result();
      $userData = mysqli_fetch_assoc($getUserDataResult)
      ?>

        <form method="post">
        <h3>Edycja</h3>
        <div id="role_div" class="input_container">
          <select class="role_input" name="role">
          <?php 
          
          $getAllRoles = $connect->prepare("SELECT * FROM `role`");
          $getAllRoles->execute();
          $getAllRolesResult = $getAllRoles->get_result();
          while($row = mysqli_fetch_assoc($getAllRolesResult)){
            if($userData['rola_id'] == $row['id_roli']) {
              echo "<option selected value=$row[id_roli]>$row[nazwa_roli]</option>";
            }else {
              echo "<option value=$row[id_roli]>$row[nazwa_roli]</option>";
            }
          }

          ?>
          </select>
        </div>
        <div id="status_div" class="input_container">
          <select class="role_input" name="status">
          <?php 
          
          $getAllStatuses = $connect->prepare("SELECT * FROM statusy");
          $getAllStatuses->execute();
          $getAllStatusesResult = $getAllStatuses->get_result();
          while($row = mysqli_fetch_assoc($getAllStatusesResult)){
            if($userData['status_id'] == $row['id_statusu']) {
              echo "<option selected value=$row[id_statusu]>$row[nazwa_statusu]</option>";
            }else {
              echo "<option value=$row[id_statusu]>$row[nazwa_statusu]</option>";
            }
          }

          ?>
          </select>
        </div>
        <input type="hidden" name="hiddenId" value="<?php echo $id?>">
        <input type="submit" value="Zapisz" name="editBtn">
        </form>
      <?php
    }

    if(isset($_POST['hiddenId']) && isset($_POST['editBtn'])){
      $id = $_POST['hiddenId'];
      $updateUserQuery = $connect->prepare("
        UPDATE `uzytkownicy` SET `id_rolu`=?,`id_statusu`=? WHERE `uzytkownicy`.id_uzytkownika = ?
      ");
      $updateUserQuery->bind_param('iii',$_POST['role'],$_POST['status'],$id);
      $updateUserQuery->execute();
      echo $updateUserQuery->get_result();
      header("location: {$_SERVER['PHP_SELF']}");

    }
    
    ?>

    <table>
      <tr>
        <th>
          ID
        </th>
        <th>
          Email
        </th>
        <th>
          Imię
        </th>
        <th>
          Nazwisko
        </th>
        <th>
          Rola
        </th>
        <th>
          Status
        </th>
        <th></th>
      </tr>
      <?php 
      
        $findAllUsersQuery = $connect->prepare("
          SELECT 
            `uzytkownicy`.id_uzytkownika AS 'id',
            `uzytkownicy`.email AS 'email',
            `profile`.imie AS 'imie',
            `profile`.nazwisko AS 'nazwisko',
            `statusy`.nazwa_statusu AS 'status',
            `role`.nazwa_roli AS 'rola'
          FROM `uzytkownicy`
          INNER JOIN `profile` ON `uzytkownicy`.id_profilu = `profile`.id_profilu
          INNER JOIN `statusy` ON `uzytkownicy`.id_statusu = `statusy`.id_statusu
          INNER JOIN `role` ON `uzytkownicy`.id_rolu = `role`.id_roli
          WHERE `uzytkownicy`.id_rolu <> 3
          ORDER BY `uzytkownicy`.id_uzytkownika
        ");
        $findAllUsersQuery->execute();
        $findAllUsersResult = $findAllUsersQuery->get_result();
        while($row = mysqli_fetch_assoc($findAllUsersResult)){
          ?>

          <tr>
            <td><?php echo $row['id'] ?></td>
            <td><?php echo $row['email'] ?></td>
            <td><?php echo $row['imie'] ?></td>
            <td><?php echo $row['nazwisko'] ?></td>
            <td><?php echo $row['rola'] ?></td>
            <td><?php echo $row['status'] ?></td>
            <td><a href="./?user_id=<?php echo $row['id']?>">Edytuj</a></td>
          </tr>

          <?php
        }

      ?>
    </table>
    <h2>Klasy</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Nazwa</th>
      </tr>
      <?php 
        if(isset($_POST['addClassbtn1']) && !empty($_POST['class'])){
          $addClassSymbolQuery = $connect->prepare("INSERT INTO symbole_klas (nazwa_symbolu_klasy) values (?)");
          $addClassSymbolQuery->bind_param('s',$_POST['class']);
          $addClassSymbolQuery->execute();
          $newId= intval($connect->insert_id);
          $addClassQuery = $connect->prepare("INSERT INTO klasy (id_symbolu_klasy) values (?)");
          $addClassQuery->bind_param('i',$newId);
          $addClassQuery->execute();
          header("location: {$_SERVER['PHP_SELF']}");
        }

        $findAllClassesQuery = $connect->prepare("
          SELECT `klasy`.id_klasy AS 'id', `symbole_klas`.nazwa_symbolu_klasy AS 'symbol'
          FROM klasy INNER JOIN symbole_klas ON `klasy`.id_symbolu_klasy = `symbole_klas`.id_symbolu_klasy
        ");
        $findAllClassesQuery->execute();
        $findAllClassesResult = $findAllClassesQuery->get_result();
        while($row = mysqli_fetch_assoc($findAllClassesResult)){
          ?>

          <tr>
            <td><?php echo $row['id'] ?></td>
            <td><?php echo $row['symbol'] ?></td>
          </tr>

          <?php
        }
      
      ?>
    </table>
    <button id="addClassBtn">
        Dodaj
    </button>
    <form method="post" style="display:none" id="addClassForm">
      <div id="title_div" class="input_container">
        <input type="text" name="class" id="title_input" />
      </div>
      <input type="submit" value="Zapisz" name="addClassbtn1">
    </form>
    <script>
      const addtitleBtn = document.getElementById('addClassBtn');
      addtitleBtn.addEventListener('click',()=>{
        const addtitleForm = document.getElementById('addClassForm');
        addtitleForm.style.display = "block";
      })

      const titleDiv = document.getElementById('title_div');
      const titleInput = document.getElementById('title_input')

      titleInput.addEventListener('input',()=>{
        if (titleInput.value != null && titleInput.value.length != 0)
        {
          titleDiv.classList.add('active');
        }
        else {
          titleDiv.classList.remove('active')
        }
      })
    </script>
  </body>
</html>

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

    if(!$data[3]){
      header('Location: ../student/add_profile.php');
    }

    require('../scripts/load_profile.php');

    if($data[2] == 1){
      

      if(isset($_POST['delete_acc'])){
        $deleteProfileQuery = $connect->prepare("UPDATE `uzytkownicy` SET `id_statusu`=? WHERE `id_uzytkownika`=?");
        $deleteProfileQuery->bind_param('ii',$x = 3,$data[0]);
        $deleteProfileQuery->execute();
      }
    }else if($data[2] == 2){
      // wywołaj jak konto nieaktywne
      header('Location: ../login.php');
    }else if($data[2] == 3){
      // wywolaj jak konto usuniete
      header('Location: ../login.php');
    }else {
      // nieobsluzony przypadek (jeszcze nwm czy zostawie)
    }

    
  }

  if(isset($_POST['changeProfileBtn']) && !empty($_POST['newClass']) && !empty($_POST['newName']) && !empty($_POST['newLname']) && !empty($_POST['newDesc']) && $data[1]==1){
    $changeProfileDataQuery = $connect->prepare("UPDATE `profile` SET `imie`=?,`nazwisko`=?,`opis`=?,`id_klasy`=? WHERE `id_profilu`=?");
    $changeProfileDataQuery->bind_param('sssii',$_POST['newName'],$_POST['newLname'],$_POST['newDesc'],$_POST['newClass'],$data[3]);
    $changeProfileDataQuery->execute();
    $changeProfileDataResult = $changeProfileDataQuery->get_result();
    Header('Location: '.$_SERVER['PHP_SELF']);
  }else if(isset($_POST['changeProfileBtn']) && !empty($_POST['class2']) && !empty($_POST['newName']) && !empty($_POST['newLname']) && !empty($_POST['newDesc']) && $data[1]==2){
    $changeProfileDataQuery = $connect->prepare("UPDATE `profile` SET `imie`=?,`nazwisko`=?,`opis`=? WHERE `id_profilu`=?");
    $changeProfileDataQuery->bind_param('sssi',$_POST['newName'],$_POST['newLname'],$_POST['newDesc'],$data[3]);
    $changeProfileDataQuery->execute();
    $changeProfileDataResult = $changeProfileDataQuery->get_result();
    
    $deleteClassTeacherRelationsQuery = $connect->prepare("DELETE FROM `klasy-nauczyciele` WHERE id_nauczyciela = ?");
    $deleteClassTeacherRelationsQuery->bind_param('i',$profile['id_profilu']);
    $deleteClassTeacherRelationsQuery->execute();

    foreach ($_POST['class2'] as $selectedOption){
      echo $selectedOption."\n";
      $classTeacherQuery = $connect->prepare("INSERT INTO `klasy-nauczyciele`(`id_nauczyciela`, `id_klasy`) VALUES (?,?)");
      $classTeacherQuery->bind_param('ii',$profile['id_profilu'],$selectedOption);
      $classTeacherQuery->execute();
    }
    Header('Location: '.$_SERVER['PHP_SELF']);
  }
?>

<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wordy | Profil</title>
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
    <?php include('../header.html') ?>
    <div id="profile-settings-box" class="box">
      <h2>Ustawienia profilu</h2>
      <section>
        <form class="" action="" method="post">
          <div id="name_div" class="input_container">
            <input type="text" name="newName" id="name_input" value="<?php echo $profile['imie']?>">
          </div>
          <div id="lname_div" class="input_container">
            <input type="text" name="newLname" id="lname_input" value="<?php echo $profile['nazwisko']?>">
          </div>
          <div id="profdesc_div" class="input_container">
            <input type="text" name="newDesc" id="profdesc_input" value="<?php echo $profile['opis']?>">
          </div>
          <!-- to bedzie widzial tylko uczen, bo moze wybrac jedna klase -->
          <?php  if($data[1] == 1){
          ?>
          <div id="class_div" class="input_container">

            <select name="newClass" id="class_input">
              <option></option>
              <?php 
                $classes_query = $connect->prepare("
                  SELECT id_klasy, nazwa_symbolu_klasy
                  FROM klasy as a INNER JOIN symbole_klas ON a.id_symbolu_klasy = symbole_klas.id_symbolu_klasy
                ");
                $classes_query->execute();
                $classes_result = $classes_query->get_result();
                while($row = mysqli_fetch_assoc($classes_result)){
                  if($row['id_klasy'] == $profile['id_klasy']){
                    echo "<option selected value='$row[id_klasy]'>$row[nazwa_symbolu_klasy]</option>";
                  }else {
                    echo "<option value='$row[id_klasy]'>$row[nazwa_symbolu_klasy]</option>";
                  }
                  
                }
              ?>
            </select>
          </div>
          <?php  
            }else if($data[1] == 2){
          ?>
          <!-- koniec -->
          <!-- to bedzie widzial tylko nauczyciel, bo moze uczyć wiele klas -->
            <div id="class2_div" class="input_container">
              Wybór klas
              <select name="class2[]" id="class2_input" multiple style="width:100%">
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
          <?php } ?>
          <input type="submit" name="changeProfileBtn" value="Wyślij">
        </form>
        <form class="acc_delete" action="" method="post">
          <input type="submit" name="delete_acc" value="Usuń konto">
        </form>
      </section>
    </div>
  </body>
  <script type="text/javascript">
  const nameDiv = document.getElementById('name_div');
  const nameInput = document.getElementById('name_input')

  if (nameInput.value != null && nameInput.value.length != 0)
  {
    nameDiv.classList.add('active');
  }
  else {
    nameDiv.classList.remove('active')
  }

  nameInput.addEventListener('input',()=>{
    if (nameInput.value != null && nameInput.value.length != 0)
    {
      nameDiv.classList.add('active');
    }
    else {
      nameDiv.classList.remove('active')
    }
  })

  const classDiv = document.getElementById('class_div');
  const classInput = document.getElementById('class_input')
  if(classInput && classDiv) {
    if (classInput.value != null && classInput.value.length != 0)
  {
    classDiv.classList.add('active');
  }
  else {
    classDiv.classList.remove('active')
  }

  classInput.addEventListener('input',()=>{
    if (classInput.value != null && classInput.value.length != 0)
    {
      classDiv.classList.add('active');
    }
    else {
      classDiv.classList.remove('active')
    }
  })
  }

  

  const lnameDiv = document.getElementById('lname_div');
  const lnameInput = document.getElementById('lname_input')

  if (lnameInput.value != null && lnameInput.value.length != 0)
  {
    lnameDiv.classList.add('active');
  }
  else {
    lnameDiv.classList.remove('active')
  }

  lnameInput.addEventListener('input',()=>{
    if (lnameInput.value != null && lnameInput.value.length != 0)
    {
      lnameDiv.classList.add('active');
    }
    else {
      lnameDiv.classList.remove('active')
    }
  })

  const profdescDiv = document.getElementById('profdesc_div');
  const profdescInput = document.getElementById('profdesc_input')

  if (profdescInput.value != null && profdescInput.value.length != 0)
  {
    profdescDiv.classList.add('active');
  }
  else {
    profdescDiv.classList.remove('active')
  }

  profdescInput.addEventListener('input',()=>{
    if (profdescInput.value != null && profdescInput.value.length != 0)
    {
      profdescDiv.classList.add('active');
    }
    else {
      profdescDiv.classList.remove('active')
    }
  })
  </script>
</html>

<?php 
session_start();
$connect = mysqli_connect("localhost", "root", "", "wiai2");
mysqli_set_charset($connect, "utf8");
if(!isset($_SESSION['user'])){
  header('Location: ../login.php');
}else {
  $data = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
  $data = explode('!//#',$data);
  if(isset($_GET['set_id'])){
    require('../scripts/reload_user.php');
    if(!$data[3]){
      header('Location: ../student/add_profile.php');
    }
    if($data[2] == 1){
      require('../scripts/load_profile.php');
      $res;
      $zestaw =$_GET['set_id'];
      if($data[1]==1){
        $klasa = $profile['id_klasy'];
        
        $checkQuery = $connect->prepare("SELECT `id_klasa_zestaw`, `id_zestawu`, `id_klasy` FROM `klasy-zestawy` WHERE `id_zestawu`=? AND `id_klasy`=?");
        $checkQuery->bind_param('ii',$zestaw,$klasa);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();
        $res = mysqli_fetch_assoc($checkResult);
      }else if($data[1]==2){
        $profil= $profile['id_profilu'];
        $checkQuery = $connect->prepare("
          SELECT `klasy-zestawy`.id_klasa_zestaw, `klasy-zestawy`.id_zestawu, `klasy-zestawy`.id_klasy
          FROM `klasy-zestawy` 
          INNER JOIN `klasy-nauczyciele` ON `klasy-nauczyciele`.id_klasy = `klasy-zestawy`.id_klasy
          WHERE `klasy-zestawy`.`id_zestawu`=? AND `klasy-nauczyciele`.`id_nauczyciela` = ?");
        $checkQuery->bind_param('ii',$zestaw,$profil);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();
        $res = mysqli_fetch_assoc($checkResult);
      }
    
      if(!$res){
        if($data[1] == 2) {
          header('Location: ../teacher');
        }else if($data[1]==1){
          header('Location: ../student');
        }else if($data[1]==3){
          header('Location: ../admin');
        }
      }else {
        $fetchFlashcardsQuery = $connect->prepare("SELECT `id_fiszki`,`oryginal`,`tlumaczenie` FROM `fiszki` WHERE `id_zestawu`=?");
        $fetchFlashcardsQuery->bind_param('i',$zestaw);
        $fetchFlashcardsQuery->execute();
        $fetchFlashcardsResult = $fetchFlashcardsQuery->get_result();
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
  }else {
    if($data[1] == 2) {
      header('Location: ../teacher');
    }else if($data[1]==1){
      header('Location: ../student');
    }else if($data[1]==3){
      header('Location: ../admin');
    }
  }
}

?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wordy | Zestaw</title>
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
    <header>
      <h1>Lista fiszek</h1>
    </header>
      <div id="flashcards-set" class="box">
      <!-- jesli jestes uczniem to sie mozesz uczyc, jak nauczycielem to przecież już wiesz wsyzstko :) -->
      <h2><a href="./show.php?set_id=<?php echo $zestaw?>">Ucz się <i class="fas fa-arrow-right"></i></a></h2>
      <section class="active">
        <?php  
          $i = 1;
          while($flashcardsResult = mysqli_fetch_assoc($fetchFlashcardsResult)){
            ?>
            
              <div>
                <p>
                  <span><?php echo $i ?></span>
                  <?php if($data[1]==2) echo "<i class='fas fa-trash'></i>" ?>
                </p>
                <p>
                  <span><?php echo $flashcardsResult['oryginal']?></span> 
                  <i class="fas fa-arrow-right"></i> 
                  <span><?php echo $flashcardsResult['tlumaczenie']?></span>
                </p>
              </div>
            <?php
            $i++;
          }
        ?>
      </section>
    </div>
    <?php @include('../nav.php') ?>
  </body>
</html>

<script type="text/javascript">

</script>

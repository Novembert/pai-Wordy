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
        $fetchFlashcardsQuery = $connect->prepare("SELECT `oryginal`,`tlumaczenie` FROM `fiszki` WHERE `id_zestawu`=?");
        $fetchFlashcardsQuery->bind_param('i',$zestaw);
        $fetchFlashcardsQuery->execute();
        $fetchFlashcardsResult = $fetchFlashcardsQuery->get_result();
        // var_dump(mysqli_fetch_assoc($fetchFlashcardsResult));
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
    <title>Wordy | Nauka</title>
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
    <div id="container">
      <div id="show" class="box">
        <!-- jesli jestes uczniem to sie mozesz uczyc, jak nauczycielem to przecież już wiesz wsyzstko :) -->
        <span id="word" class="unselectable">

        </span>
      </div>
      <p id="arrows-container">
        <i class="fas fa-arrow-left"></i>
        <i class="fas fa-arrow-right"></i>
      </p>
      <p id="pages">
        <span id="actual"></span>
        /
        <span id="max"></span>
      </p>
    </div>
  </body>
  <?php 

      $flashcardsArray = array();               
      while ($row = mysqli_fetch_assoc($fetchFlashcardsResult)) {
        array_push($flashcardsArray,$row);
      }
    ?>
  <script type="text/javascript">
    
    let flashcards = <?php echo json_encode($flashcardsArray)?>

    const word = document.querySelector('#word');
    word.textContent = flashcards[0].oryginal;

    let i = 0;
    let oryginal = true;

    const max = document.querySelector('#max');
    max.textContent = flashcards.length;

    const actual = document.querySelector('#actual')
    actual.textContent = i + 1;

    const arr_left = document.querySelector('.fa-arrow-left')
    const arr_right = document.querySelector('.fa-arrow-right')
    const show = document.querySelector('#show')

    arr_left.classList = 'd-none'

    arr_right.addEventListener('click',()=>{
      changePage('right');
    })

    arr_left.addEventListener('click',()=>{
      changePage('left');
    })

    show.addEventListener('click',()=>{
      changeWord();
    })

    function changePage(direction) {
      if(i == 0) {
        if(direction == 'left'){

        }else if (direction == 'right') {
          i++;
        }
      }else if (i == flashcards.length - 1){
        if(direction == 'right'){

        }else if (direction == 'left') {
          i--;
        }
      }else {
        if(direction == "left") {
          i--;
        }else if (direction == 'right'){
          i++
        }
      }

      if(i == 0) {
        arr_left.classList = 'd-none'
      } else {
        arr_left.classList = 'fas fa-arrow-left'
      }

      if (i == flashcards.length - 1) {
        arr_right.classList = 'd-none'
      } else {
        arr_right.classList = 'fas fa-arrow-right'
      }
      word.textContent = flashcards[i].oryginal;
      oryginal = true;
      actual.textContent = i + 1;
    }

    function changeWord() {
      console.log(oryginal)
      if(oryginal == true){
        word.classList.toggle('hide')
        window.setTimeout( ()=>{word.textContent = flashcards[i].tlumaczenie; word.classList.toggle('hide')} ,100)
        oryginal = false;
      }else{
        word.classList.toggle('hide')
        window.setTimeout( ()=>{word.textContent = flashcards[i].oryginal; word.classList.toggle('hide')} ,100)
        oryginal = true;
      }
    }

  </script>
</html>
<?php @include('../nav.php') ?>

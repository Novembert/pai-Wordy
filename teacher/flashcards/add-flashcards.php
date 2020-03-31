<?php
    $connect = mysqli_connect("localhost", "root", "", "wiai2");
    mysqli_set_charset($connect, "utf8");
    session_start();
    if(!isset($_SESSION['user'])){
      header('Location: ../../login.php');
    }else {
      
      require('../../scripts/reload_user.php');
  
      $data = openssl_decrypt($_SESSION['user'],'rc4-hmac-md5','ptaki_lataja_kluczem');
      $data = explode('!//#',$data);

      require('../../scripts/load_profile.php');
  
      if($data[2] == 1){
        if($data[1] != 2) {
          if($data[1] == 1) {
            header('Location: ../../student');
          }else {
            // nieobsluzony przypadek (jeszcze nwm czy zostawie)
          }
        }
        if(!$data[3]){
          header('Location: ../add_profile.php');
        }
        if(isset($_POST['create_set_btn'])){
          $addNewSetQuery = $connect->prepare("INSERT INTO `zestawy_fiszek`(`nazwa_zestawu`, `id_jezyka`) VALUES (?,?)");
          $addNewSetQuery->bind_param('si',$_POST['title2'],$_POST['lang']);
          $addNewSetQuery->execute();
          $newSetId = intval($connect->insert_id);

          foreach ($_POST['class2'] as $selectedOption){
            $addClassSetRelationQuery = $connect->prepare("INSERT INTO `klasy-zestawy`(`id_zestawu`, `id_klasy`) VALUES (?,?)");
            $addClassSetRelationQuery->bind_param("ii",$newSetId,$selectedOption);
            $addClassSetRelationQuery->execute();
          }

          $originalsArr = $_POST['flashcard-original'];
          $translationsArr = $_POST['flashcard-translation'];

          for($count = 0;$count < sizeof($originalsArr);$count++){
            $addFlashcardsToSetQuery = $connect->prepare("INSERT INTO `fiszki`(`id_zestawu`, `oryginal`, `tlumaczenie`) VALUES (?,?,?)");
            $addFlashcardsToSetQuery->bind_param("iss",$newSetId,$originalsArr[$count],$translationsArr[$count]);
            $addFlashcardsToSetQuery->execute();
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wordy | Tworzenie Zestawu Fiszek</title>
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
    <form method="post">
    <div class="d-flex">
      <div id="add-flashcards" class="box">
        <h2><?php echo $_GET['title'] ?></h2>
        <div id="lang_div" class="input_container">
        <select name="lang" id="lang_input" class="class_input">
            <?php 
              $langs_query = $connect->prepare("
              SELECT * FROM jezyki
            ");
            $langs_query->execute();
            $langs_result = $langs_query->get_result();
            while($row = mysqli_fetch_assoc($langs_result)){
              echo "<option value='$row[id_jezyka]'>$row[nazwa_jezyka]</option>";
            }
            ?>
          </select>
        </div>
        <input type="hidden" name="title2" value="<?php echo $_GET['title'] ?>">
        <section class="active">
          <section class="flashcards">
            <section class="flashcard-input">
              <h3><span>Definicja 1</span><i class="fas fa-trash"></i></h3>
              <input type="text" name="flashcard-original[]" placeholder="oryginał">
              <input type="text" name="flashcard-translation[]" placeholder="tłumaczenie">
            </section>
          </section>
          <button>Nowa +</button>
        </section>
      </div>

      <div id="select-classes" class="box">
        <h2>Wybierz klasy</h2>
        <div id="class2_div" class="input_container">
          <select name="class2[]" id="class2_input" multiple>
            <?php 
              $classes_query = $connect->prepare("
              SELECT a.id_klasy AS `klasa`, nazwa_symbolu_klasy 
              FROM klasy as a 
              INNER JOIN symbole_klas ON a.id_symbolu_klasy = symbole_klas.id_symbolu_klasy 
              INNER JOIN `klasy-nauczyciele` ON a.id_klasy = `klasy-nauczyciele`.id_klasy 
              WHERE `klasy-nauczyciele`.id_nauczyciela = ?
              ");
              $classes_query->bind_param('i',$profile['id_profilu']);
              $classes_query->execute();
              $classes_result = $classes_query->get_result();
              while($row = mysqli_fetch_assoc($classes_result)){
                echo "<option value='$row[klasa]'>$row[nazwa_symbolu_klasy]</option>";
              }
            ?>
          </select>
        </div>
        <input type="submit"value="Stwórz"name="create_set_btn">
      </div>
    </div>
    </form>
  </body>
</html>
<?php @include('../../nav.php') ?>
<script type="text/javascript">
  const h2 = document.querySelector('#add-flashcards>h2');

  const newBtn = document.querySelector('button');
  const flashcards_list = document.querySelector('form .flashcards');

  let i = 2;

  newBtn.addEventListener('click',e=>{
    e.preventDefault();
    let section = document.createElement('section');
    section.classList.add('flashcard-input');

    let h3 = document.createElement('h3');
    let definition = document.createElement('span');
    definition.textContent = `Definicja ${i}`;
    let trash = document.createElement('i');
    trash.classList.add('fas')
    trash.classList.add('fa-trash')
    h3.appendChild(definition);
    h3.appendChild(trash);


    section.appendChild(h3);
    let input1 = document.createElement('input');
    input1.setAttribute('type','text')
    input1.setAttribute('name',`flashcard-original[]`)
    input1.setAttribute('placeholder',`oryginał`)
    let input2 = document.createElement('input');
    input2.setAttribute('type','text')
    input2.setAttribute('name',`flashcard-translation[]`)
    input2.setAttribute('placeholder',`tłumaczenie`)

    section.appendChild(input1);
    section.appendChild(input2);
    i++;
    flashcards_list.appendChild(section)
  })

  const langDiv = document.getElementById('lang_div');
  const langInput = document.getElementById('lang_input')

  if (langInput.value != null && langInput.value.length != 0)
    {
      langDiv.classList.add('active');
    }
    else {
      langDiv.classList.remove('active')
    }

  langInput.addEventListener('input',()=>{
    if (langInput.value != null && langInput.value.length != 0)
    {
      langDiv.classList.add('active');
    }
    else {
      langDiv.classList.remove('active')
    }
  })
</script>

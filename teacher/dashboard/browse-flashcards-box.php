<?php require('./../scripts/load_profile.php'); ?>

<div id="browse-flashcards" class="box">
  <h2>Twoje zestawy</h2>
  <section class="active">
    <p class="filter">Filtruj<i class="fas fa-chevron-down"></i></p>
    <form id="filter1" class="filter" method="post">
      <div class="input_container" id="lang_div">
        <select class="" name="lang_select" id="lang_input">
          <option value=""></option>
          <?php

            $getAllLanguagesQuery = $connect->prepare("SELECT * FROM jezyki");
            $getAllLanguagesQuery->execute();
            $getAllLanguagesResult = $getAllLanguagesQuery->get_result();
            // var_dump($getAllLanguagesResult);
            while($lang = mysqli_fetch_assoc($getAllLanguagesResult)){
              echo "<option value='$lang[id_jezyka]'>".$lang['nazwa_jezyka']."</option>";
            }

          ?>
        </select>
      </div>
      <div class="input_container" id="title2_div">
        <input type="text" name="title" id="title2_input">
      </div>
      <div class="filter_btns">
        <button class="submit_filter">Filtruj</button>
        <button class="clear_filter">Wyczyść</button>
      </div>
    </form>
    <table>
      <tr>
        <th>Język</th>
        <th>Tytuł</th>
      </tr>
      <?php 
        if(isset($_GET['lang'])&&isset($_GET['title'])){
          $getSelectedFlashcardSetsOfThisUserQuery = $connect->prepare("
            SELECT * FROM `zestawy_fiszek`
            INNER JOIN `klasy-zestawy` ON `klasy-zestawy`.id_zestawu=`zestawy_fiszek`.id_zestawu_fiszek
            INNER JOIN `jezyki` ON `jezyki`.id_jezyka=`zestawy_fiszek`.id_jezyka
            INNER JOIN `klasy-nauczyciele` ON `klasy-zestawy`.id_klasy = `klasy-nauczyciele`.id_klasy
            WHERE `klasy-nauczyciele`.id_nauczyciela = ? AND `zestawy_fiszek`.id_jezyka = ? AND `zestawy_fiszek`.nazwa_zestawu LIKE ?
          ");
          $y = "%".$_GET['title']."%";
          $getSelectedFlashcardSetsOfThisUserQuery->bind_param(
            'iis',
            $profile['id_profilu'],
            $_GET['lang'],
            $y
          );
          $getSelectedFlashcardSetsOfThisUserQuery->execute();
          $getSelectedFlashcardSetsOfThisUserResult = $getSelectedFlashcardSetsOfThisUserQuery->get_result();
          $flashcardsResult = $getSelectedFlashcardSetsOfThisUserResult;
        }else {
          $getAllFlashcardSetsOfThisUserQuery = $connect->prepare("
            SELECT * FROM `zestawy_fiszek`
            INNER JOIN `klasy-zestawy` ON `klasy-zestawy`.id_zestawu=`zestawy_fiszek`.id_zestawu_fiszek
            INNER JOIN `jezyki` ON `jezyki`.id_jezyka=`zestawy_fiszek`.id_jezyka
            INNER JOIN `klasy-nauczyciele` ON `klasy-zestawy`.id_klasy = `klasy-nauczyciele`.id_klasy
            WHERE `klasy-nauczyciele`.id_nauczyciela = ?
          ");
          $getAllFlashcardSetsOfThisUserQuery->bind_param('i',$profile['id_profilu']);
          $getAllFlashcardSetsOfThisUserQuery->execute();
          $getAllFlashcardSetsOfThisUserResult = $getAllFlashcardSetsOfThisUserQuery->get_result();
          $flashcardsResult = $getAllFlashcardSetsOfThisUserResult;
        }
        while($flashcardSet = mysqli_fetch_assoc($flashcardsResult)){
          ?>
            <tr>
              <td class="language"><?php echo strtoupper(substr($flashcardSet['nazwa_jezyka'],0,3)) ?></td>
              <td><a href="/Wordy/flashcards/set.php?set_id=<?php echo $flashcardSet['id_zestawu_fiszek']?>"><?php echo $flashcardSet['nazwa_zestawu']; ?></a></td>
            </tr>
          <?php
        }
      ?>
    </table>
  </section>
  <div id="excol-arrow-1" class="excol-arrow active">
    <i class="fas fa-chevron-down"></i>
  </div>
  <script type="text/javascript">

    const btnFilter = document.querySelector('.submit_filter')
    const btnClear = document.querySelector('.clear_filter')
    const langDiv = document.getElementById('lang_div');
    const langInput = document.getElementById('lang_input')
    const titleDiv2 = document.getElementById('title2_div');
    const titleInput2 = document.getElementById('title2_input')
    const filter1 = document.querySelector('#filter1')
    const filterOpen = document.querySelector('#browse-flashcards p.filter')
    const filterOpenArrow = document.querySelector('#browse-flashcards p.filter i')
    const section1 = document.querySelector('#browse-flashcards section');
    const arr1 = document.querySelector('#excol-arrow-1');

    const fillFilterInputWithData = function(data){
      let selectedLang = document.querySelector('#lang_input');
      let title2 = document.querySelector('#title2_input');

      if(data!=null) {
        data = sessionStorage.getItem('flashcards-filter').split('|')
        if(data[0] != undefined){
          selectedLang.value = data[0];
        }else {
          selectedLang.value = ''
        }
        if(data[1] != ''){
          title2.value = data[1];
        }else {
          title2.value = ''
        }
      }
    }

    const checkLabels = function() {
      if (langInput.value != null && langInput.value.length != 0)
      {
        langDiv.classList.add('active');
      }
      else {
        langDiv.classList.remove('active')
      }
      if (titleInput2.value != null && titleInput2.value.length != 0)
      {
        titleDiv2.classList.add('active');
      }
      else {
        titleDiv2.classList.remove('active')
      }
    }

    // wczytanie starych filtrow
    if(sessionStorage.getItem('flashcards-filter')){
      fillFilterInputWithData(sessionStorage.getItem('flashcards-filter'))
    }

    // dodanie nowego filtru
    btnFilter.addEventListener('click',(e)=>{

      let selectedLang = document.querySelector('#lang_input');
      let title2 = document.querySelector('#title2_input');
      e.preventDefault();
      if(selectedLang.options[selectedLang.selectedIndex]){
        selectedLang = selectedLang.options[selectedLang.selectedIndex].value
      } else {
        selectedLang = undefined
      }
      if(title2.value){
        title2 = title2.value
      }else {
        title2= ''
      }

      sessionStorage.setItem('flashcards-filter',`${selectedLang}|${title2}`)

      let url = new URL(window.location.href);
      let query_string = url.search;
      let search_params = new URLSearchParams(query_string);
      search_params.set('lang',selectedLang)
      search_params.set('title',title2)
      url.search = search_params.toString();
      let newurl = url.toString();
      window.location.href = newurl
    })

    btnClear.addEventListener('click',(e)=>{
      let selectedLang = document.querySelector('#lang_input');
      let title2 = document.querySelector('#title2_input');
      e.preventDefault();
      sessionStorage.removeItem('flashcards-filter');
      selectedLang.value = null
      title2.value = null
      checkLabels();
      let url = new URL(window.location.href);
      let query_string = url.search;
      let search_params = new URLSearchParams(query_string);
      search_params.delete('lang')
      search_params.delete('title')
      url.search = search_params.toString();
      let newurl = url.toString();
      window.location.href = newurl
    })

    // koniec cookies itp

    arr1.addEventListener('click',()=>{
      arr1.classList.toggle('active')
      section1.classList.toggle('active');
    })

    filterOpen.addEventListener('click',()=>{
      filter1.classList.toggle('active')
      filterOpenArrow.classList.toggle('active')
    })

    checkLabels()

    titleInput2.addEventListener('input',()=>{
      checkLabels()
    })

    langInput.addEventListener('input',()=>{
      checkLabels()
    })

  </script>
</div>

<div id="browse-flashcards" class="box">
  <h2>Przeglądanie fiszek</h2>
  <section class="active">
    <p class="filter">Filtruj<i class="fas fa-chevron-down"></i></p>
    <form id="filter1" class="filter" method="post">
      <div class="input_container" id="lang_div">
        <select class="" name="lang_select" id="lang_input">
          <option value=""></option>
          <option value="ENG">ENG</option>
          <option value="ESP">ESP</option>
          <option value="DEU">DEU</option>
          <option value="POR">POR</option>
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
        <th class="sorted language">Język<i class="fas fa-chevron-down"/></th>
        <th>Tytuł</th>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 1</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 2</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 3</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 4</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 5</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 6</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 7</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 8</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 8</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 8</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 8</td>
      </tr>
      <tr>
        <td class="language">ESP</td>
        <td>Repetytorium - Unit 8</td>
      </tr>
    </table>
  </section>
  <div id="excol-arrow-1" class="excol-arrow active">
    <i class="fas fa-chevron-down"></i>
  </div>
  <script type="text/javascript">

    // flitrowanie - cookies

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
    })

    btnClear.addEventListener('click',(e)=>{
      let selectedLang = document.querySelector('#lang_input');
      let title2 = document.querySelector('#title2_input');
      e.preventDefault();
      sessionStorage.removeItem('flashcards-filter');
      selectedLang.value = null
      title2.value = null
      checkLabels();
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

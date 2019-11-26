<!DOCTYPE htmls
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
    <form class="" action="../../teacher" method="post">
    <div id="add-flashcards" class="box">
      <section class="active">

          <section class="flashcards">
            <section class="flashcard-input">
              <h3><span>Definicja 1</span><i class="fas fa-trash"></i></h3>
              <input type="text" name="flashcard-original-1" placeholder="oryginał">
              <input type="text" name="flashcard-translation-1" placeholder="tłumaczenie">
            </section>
          </section>
          <button>Nowa +</button>


      </section>
    </div>

    <div id="select-classes" class="box">
      <h2>Wybierz klasy</h2>
      <div id="class2_div" class="input_container">
        <select name="class2" id="class2_input" multiple>
          <option value="3a">3A</option>
          <option value="4a">4A</option>
          <option value="2c">2C</option>
          <option value="3d">3D</option>
          <option value="3a">3A</option>
          <option value="4a">4A</option>
          <option value="2c">2C</option>
          <option value="3d">3D</option>
        </select>
      </div>
      <input type="submit"value="Stwórz">
    </div>
    </form>
  </body>
</html>
<?php @include('../../nav.php') ?>
<script type="text/javascript">
  const h2 = document.querySelector('#add-flashcards>h2');
  let title = sessionStorage.flashcards_adding_title

  const newBtn = document.querySelector('button');
  const flashcards_list = document.querySelector('#add-flashcards form .flashcards');

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
    input1.setAttribute('name',`flashcard-original-${i}`)
    input1.setAttribute('placeholder',`oryginał`)
    let input2 = document.createElement('input');
    input2.setAttribute('type','text')
    input2.setAttribute('name',`flashcard-translation-${i}`)
    input2.setAttribute('placeholder',`tłumaczenie`)

    section.appendChild(input1);
    section.appendChild(input2);
    i++;
    flashcards_list.appendChild(section)
  })

  h2.textContent = title;
</script>

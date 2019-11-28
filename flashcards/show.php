<!DOCTYPE htmls
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
  <script type="text/javascript">
    let flashcards = [
      {
        original: 'Hello',
        translation: 'Witaj'
      },
      {
        original: 'World',
        translation: 'Świat'
      },
      {
        original: 'PHP',
        translation: 'Dziwny język'
      },
      {
        original: 'JavaScript',
        translation: 'Super język'
      },
    ]

    const word = document.querySelector('#word');
    word.textContent = flashcards[0].original;

    let i = 0;
    let original = true;

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
      word.textContent = flashcards[i].original;
      original = true;
      actual.textContent = i + 1;
    }

    function changeWord() {
      console.log(original)
      if(original == true){
        word.classList.toggle('hide')
        window.setTimeout( ()=>{word.textContent = flashcards[i].translation; word.classList.toggle('hide')} ,100)
        original = false;
      }else{
        word.classList.toggle('hide')
        window.setTimeout( ()=>{word.textContent = flashcards[i].original; word.classList.toggle('hide')} ,100)
        original = true;
      }
    }

  </script>
</html>
<?php @include('../nav.php') ?>

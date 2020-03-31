<div id="add-flashcards-box" class="box">
  <h2>Dodawanie fiszek</h2>
  <section>
    <form method="get" action="./flashcards/add-flashcards.php">
      <div id="title_div" class="input_container">
        <input type="text" name="title" id="title_input">
      </div>
      <input type="submit" value="Stwórz" name="create_flashcard_submit">
    </form>

  </section>
  <div id="excol-arrow-2" class="excol-arrow">
    <i class="fas fa-chevron-down"></i>
  </div>
  <script type="text/javascript">
    const section2 = document.querySelector('#add-flashcards-box section');
    const arr2 = document.querySelector('#excol-arrow-2');
    arr2.addEventListener('click',()=>{
      arr2.classList.toggle('active')
      section2.classList.toggle('active');
    })

    const titleDiv = document.getElementById('title_div');
    const titleInput = document.getElementById('title_input')

    if (titleInput.value != null && titleInput.value.length != 0)
    {
      titleDiv.classList.add('active');
    }
    else {
      titleDiv.classList.remove('active')
    }

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
</div>

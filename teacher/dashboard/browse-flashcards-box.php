<div id="browse-flashcards" class="box">
  <h2>Przeglądanie fiszek</h2>
  <section>
    <ul>
      <li>
        Repetytorium maturalne - Unit 1
      </li>
      <li>
        Repetytorium maturalne - Unit 2
      </li>
      <li>
        Repetytorium maturalne - Unit 3
      </li>
      <li>
        Repetytorium maturalne - Unit 4
      </li>
      <li>
        Repetytorium maturalne - Unit 5
      </li>
      <li>
        Repetytorium maturalne - Unit 6
      </li>
    </ul>
  </section>
  <div id="excol-arrow-1" class="excol-arrow">
    <i class="fas fa-chevron-down"></i>
  </div>
  <script type="text/javascript">
    const section1 = document.querySelector('#browse-flashcards section');
    const arr1 = document.querySelector('#excol-arrow-1');
    arr1.addEventListener('click',()=>{
      arr1.classList.toggle('active')
      section1.classList.toggle('active');
    })
  </script>
</div>

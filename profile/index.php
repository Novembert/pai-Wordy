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
        <form class="" action="." method="post">
          <div id="name_div" class="input_container">
            <input type="text" name="name" id="name_input">
          </div>
          <!-- to bedzie widzial tylko uczen, bo moze wybrac jedna klase -->
          <div id="class_div" class="input_container">

            <select name="class" id="class_input">
              <option value=""></option>
              <option value="3a">3A</option>
              <option value="4a">4A</option>
              <option value="2c">2C</option>
              <option value="3d">3D</option>
            </select>
          </div>
          <!-- koniec -->
          <!-- to bedzie widzial tylko nauczyciel, bo moze uczyć wiele klas -->
          <div id="class2_div" class="input_container">
            Wybór klas
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
          <input type="submit" name="" value="Wyślij">
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
  </script>
</html>

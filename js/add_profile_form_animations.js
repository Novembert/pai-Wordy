const imieDiv = document.getElementById('imie_div');
const imieInput = document.getElementById('imie_input')

imieInput.addEventListener('input',()=>{
  if (imieInput.value != null && imieInput.value.length != 0)
  {
    imieDiv.classList.add('active');
  }
  else {
    imieDiv.classList.remove('active')
  }
})

const nazwiskoDiv = document.getElementById('nazwisko_div');
const nazwiskoInput = document.getElementById('nazwisko_input')

nazwiskoInput.addEventListener('input',()=>{
  if (nazwiskoInput.value != null && nazwiskoInput.value.length != 0)
  {
    nazwiskoDiv.classList.add('active');
  }
  else {
    nazwiskoDiv.classList.remove('active')
  }
})

const opisDiv = document.getElementById('opis_div');
const opisInput = document.getElementById('opis_input')

opisInput.addEventListener('input',()=>{
  if (opisInput.value != null && opisInput.value.length != 0)
  {
    opisDiv.classList.add('active');
  }
  else {
    opisDiv.classList.remove('active')
  }
})

const id_klasyDiv = document.getElementById('id_klasy_div');
const id_klasyInput = document.getElementById('id_klasy_input')

id_klasyInput.addEventListener('input',()=>{
  if (id_klasyInput.value != null && id_klasyInput.value.length != 0)
  {
    id_klasyDiv.classList.add('active');
  }
  else {
    id_klasyDiv.classList.remove('active')
  }
})
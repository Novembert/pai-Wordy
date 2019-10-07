const nameInput = document.getElementById('name_input');
const nameDiv = document.getElementById('name_div');

nameInput.addEventListener('input', () => {
  if (nameInput.value != null && nameInput.value.length != 0) {
    nameDiv.classList.add('active');
  } else {
    nameDiv.classList.remove('active');
  }
});

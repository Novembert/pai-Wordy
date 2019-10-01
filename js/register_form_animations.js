const emailDiv = document.getElementById('email_div');
const emailInput = document.getElementById('email_input')


emailInput.addEventListener('input',()=>{
  if (emailInput.value != null && emailInput.value.length != 0)
  {
    emailDiv.classList.add('active');
  }
  else {
    emailDiv.classList.remove('active')
  }
})

const passwordDiv = document.getElementById('password_div');
const passwordInput = document.getElementById('password_input')

passwordInput.addEventListener('input',()=>{
  if (passwordInput.value != null && passwordInput.value.length != 0)
  {
    passwordDiv.classList.add('active');
  }
  else {
    passwordDiv.classList.remove('active')
  }
})

const emailConfDiv = document.getElementById('email_conf_div');
const emailConfInput = document.getElementById('email_conf_input');

emailConfInput.addEventListener('input',()=>{
  if (emailConfInput.value != null && emailConfInput.value.length != 0)
  {
    emailConfDiv.classList.add('active');
  }
  else {
    emailConfDiv.classList.remove('active')
  }
})

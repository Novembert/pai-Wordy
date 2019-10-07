const burger = document.getElementById('burger');
const burgerContainer = document.getElementById('burger_container');
const aside = document.querySelector('aside');
burgerContainer.addEventListener('click', () => {
  burger.classList.toggle('open');
  burgerContainer.classList.toggle('active');
  aside.classList.toggle('active');
});

document.addEventListener('keydown', e => {
  if (e.keyCode == 32) {
    burger.classList.toggle('open');
    burgerContainer.classList.toggle('active');
    aside.classList.toggle('active');
  }
});

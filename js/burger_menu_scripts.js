const burger = document.getElementById('burger');
const burgerContainer = document.getElementById('burger_container');
const aside = document.querySelector('aside');
burgerContainer.addEventListener('click', () => {
  burger.classList.toggle('open');
  burgerContainer.classList.toggle('active');
  aside.classList.toggle('active');
});

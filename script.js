const light = document.querySelector('.light');

window.addEventListener('mousemove', e => {
  light.style.left = e.clientX + 'px';
  light.style.top = e.clientY + 'px';
});
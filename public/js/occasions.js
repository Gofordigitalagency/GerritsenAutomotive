// Slide-down voor "Bekijk Het Aanbod" op de homepage
document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('btnBekijkAanbod');
  if (!btn) return;

  btn.addEventListener('click', () => {
    const hidden = document.querySelectorAll('#nieuwGrid .is-hidden');
    hidden.forEach((el, idx) => {
      el.style.display = 'block';
      el.style.maxHeight = '0px';
      el.style.overflow = 'hidden';
      el.style.transition = 'max-height .35s ease';
      requestAnimationFrame(() => { el.style.maxHeight = '1200px'; });
      setTimeout(() => { el.style.maxHeight = ''; el.style.overflow = ''; }, 450 + idx * 10);
    });
    btn.remove();
  });


  
});

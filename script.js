window.addEventListener('scroll', function () {
  const navbar = document.getElementById('mainNavbar');
  navbar.classList.toggle('scrolled', window.scrollY > 50);
});

function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('show');
}

  window.addEventListener('scroll', function () {
    const navbar = document.getElementById('mainNavbar');
    navbar.classList.toggle('scrolled', window.scrollY > 50);
  });


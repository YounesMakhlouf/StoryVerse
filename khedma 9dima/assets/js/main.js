const nav = document.querySelector('#navbar');
const navToggle = document.querySelector('.mobile-nav-toggle');
const navLinks = document.querySelectorAll('.nav-link');

// Toggle navigation menu on small screens
navToggle.addEventListener('click', () => {
    nav.classList.toggle('navbar-mobile');
});

// Close navigation menu when a link is clicked on small screens
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        nav.classList.remove('navbar-mobile');
    });
});

window.addEventListener('load', () => {
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    })
});

const nav = document.querySelector('#navbar');
const navToggle = document.querySelector('.mobile-nav-toggle');
const navLinks = document.querySelectorAll('.nav-link');
let header = document.querySelector('#header');

/**
 * Toggle navigation menu on small screens
 */
navToggle.addEventListener('click', () => {
    nav.classList.toggle('navbar-mobile');
});

/**
 * Close navigation menu when a link is clicked on small screens
 */
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        nav.classList.remove('navbar-mobile');
    });
});

/**
 * Mobile nav toggle
 */
navToggle.addEventListener('click', function () {
   // nav.classList.toggle('navbar-mobile');
    this.classList.toggle('fa-bars');
    this.classList.toggle('fa-x');
})

/**
 * Mobile nav dropdowns activate
 */
document.querySelectorAll('.navbar .dropdown > a').forEach(el => {
    el.addEventListener('click', function (e) {
        if (nav.classList.contains('navbar-mobile')) {
            e.preventDefault()
            this.nextElementSibling.classList.toggle('dropdown-active')
        }
    }, true)
})

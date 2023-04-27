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

/**
 * Navbar links active state on scroll
 */
let navbarlinks = document.querySelectorAll('#navbar .scrollto');
const navbarlinksActive = () => {
    let position = window.scrollY + 200;
    navbarlinks.forEach(navbarlink => {
        if (!navbarlink.hash) return;
        let section = document.querySelector(navbarlink.hash);
        if (!section) return;
        if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
            navbarlink.classList.add('active');
        } else {
            navbarlink.classList.remove('active');
        }
    });
};
window.addEventListener('load', navbarlinksActive);
window.addEventListener('scroll', navbarlinksActive);

/**
 * Scrolls to an element with header offset
 */
const scrollto = (el) => {
    let offset = header.offsetHeight;
    if (!header.classList.contains('header-scrolled')) {
        offset -= 20;
    }
    let elementPos = document.querySelector(el).offsetTop;
    window.scrollTo({
        top: elementPos - offset,
        behavior: 'smooth'
    });
}

/**
 * Toggle .header-scrolled class to #header when page is scrolled
 */
if (header) {
    const headerScrolled = () => {
        if (window.scrollY > 100) {
            header.classList.add('header-scrolled')
        } else {
            header.classList.remove('header-scrolled')
        }
    }
    window.addEventListener('load', headerScrolled);
    document.addEventListener('scroll', headerScrolled);
}

/**
 * Scroll with offset on links with a class name .scrollto
 */
document.querySelectorAll('.scrollto').forEach(link => {
    link.addEventListener('click', (e) => {
        if (document.querySelector(link.hash)) {
            e.preventDefault();
            let navbar = document.querySelector('#navbar');
            if (navbar.classList.contains('navbar-mobile')) {
                navbar.classList.remove('navbar-mobile');
                let navbarToggle = document.querySelector('.mobile-nav-toggle');
                navbarToggle.classList.toggle('fa-bars');
                navbarToggle.classList.toggle('fa-x');
            }
            scrollto(link.hash);
        }
    }, true);
});

/**
 * Scroll with offset on page load with hash links in the url
 */
window.addEventListener('load', () => {
    if (window.location.hash) {
        if (document.querySelector(window.location.hash)) {
            scrollto(window.location.hash);
        }
    }
});
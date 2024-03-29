const primaryHeader = document.querySelector(".primary-header");
const scrollWatcher = document.createElement("div");

scrollWatcher.setAttribute('data-scroll-watcher', '');
primaryHeader.before(scrollWatcher);

const navObserver = new IntersectionObserver((entries) => {
    console.log(entries);
    primaryHeader.classList.toggle('sticking', !entries[0].isIntersecting);
}, {rootMargin: "200px 0px 0px 0px"});

navObserver.observe(scrollWatcher);

/**
 * Navbar links active state on scroll
 */
let navbarlinks = document.querySelectorAll("#navbar .scrollto");
const navbarlinksActive = () => {
    let position = window.scrollY + 200;
    navbarlinks.forEach((navbarlink) => {
        if (!navbarlink.hash) return;
        let section = document.querySelector(navbarlink.hash);
        if (!section) return;
        if (position >= section.offsetTop && position <= section.offsetTop + section.offsetHeight) {
            navbarlink.classList.add("active");
        } else {
            navbarlink.classList.remove("active");
        }
    });
};
window.addEventListener("load", navbarlinksActive);
window.addEventListener("scroll", navbarlinksActive);
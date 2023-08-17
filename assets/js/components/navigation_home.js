const nav = document.querySelector("#navbar");
let header = document.querySelector("#header");

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

/**
 * Toggle .header-scrolled class to #header when page is scrolled
 */
if (header) {
    const headerScrolled = () => {
        if (window.scrollY > 100) {
            header.classList.add("header-scrolled");
        } else {
            header.classList.remove("header-scrolled");
        }
    };
    window.addEventListener("load", headerScrolled);
    document.addEventListener("scroll", headerScrolled);
}
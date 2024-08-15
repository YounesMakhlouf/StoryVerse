const primaryHeader = document.querySelector(".primary-header");
const scrollWatcher = document.createElement("div");

scrollWatcher.setAttribute("data-scroll-watcher", "");
primaryHeader.before(scrollWatcher);

const navObserver = new IntersectionObserver(
  (entries) => {
    console.log(entries);
    primaryHeader.classList.toggle("sticking", !entries[0].isIntersecting);
  },
  { rootMargin: "200px 0px 0px 0px" }
);

navObserver.observe(scrollWatcher);

/**
 * Navbar links active state on scroll
 */
let navbarLinks = document.querySelectorAll("#navbar .scrollto");
const navbarLinksActive = () => {
  let position = window.scrollY + 200;

  navbarLinks.forEach((navbarLink) => {
    if (!navbarLink.hash) return;
    let section = document.querySelector(navbarLink.hash);
    if (!section) return;

    if (
      position >= section.offsetTop &&
      position <= section.offsetTop + section.offsetHeight
    ) {
      navbarLink.classList.add("active");
    } else {
      navbarLink.classList.remove("active");
    }
  });
};
window.addEventListener("load", navbarLinksActive);
window.addEventListener("scroll", navbarLinksActive);

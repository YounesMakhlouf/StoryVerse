const nav = document.querySelector("#navbar");
const navToggle = document.querySelector(".mobile-nav-toggle");
const navLinks = document.querySelectorAll(".nav-link");
let header = document.querySelector("#header");

/**
 * Toggle navigation menu on small screens
 */
navToggle.addEventListener("click", () => {
  nav.classList.toggle("navbar-mobile");
});

/**
 * Close navigation menu when a link is clicked on small screens
 */
navLinks.forEach((link) => {
  link.addEventListener("click", () => {
    nav.classList.remove("navbar-mobile");
  });
});

/**
 * Mobile nav toggle
 */
navToggle.addEventListener("click", function () {
  // nav.classList.toggle('navbar-mobile');
  this.classList.toggle("fa-bars");
  this.classList.toggle("fa-x");
});

/**
 * Mobile nav dropdowns activate
 */
document.querySelectorAll(".navbar .dropdown > a").forEach((el) => {
  el.addEventListener(
    "click",
    function (e) {
      if (nav.classList.contains("navbar-mobile")) {
        e.preventDefault();
        this.nextElementSibling.classList.toggle("dropdown-active");
      }
    },
    true
  );
});

//skander's magnificent notification shower
const userId = document.querySelector(".userid").textContent.trim();
console.log("dqpdnqsnd" + userId); // Replace with the user ID you want to fetch notifications for
const notiflist = document.querySelector(".notiflist");
// Fetch notifications data from the server
fetch(`/notification/user/${userId}`)
  .then((response) => response.json())
  .then((data) => {
    if (data.length == 0) {
      console.log("nulle walah");
      notiflist.append(createNotif("no notifications yet, don't lose hope !"));
    }
    if (Array.isArray(data)) {
      data.forEach((notification) => {
        // Display the notifications data in the console au càs où

        console.log(notification);
        notiflist.append(createNotif(notification.content));
      });
    }
  })
  .catch((error) => {
    console.error(error);
  });

//creates the notification list
function createNotif(content) {
  const li = document.createElement("li");
  li.classList.add("dropdown-item");
  li.textContent = content;
  return li;
}

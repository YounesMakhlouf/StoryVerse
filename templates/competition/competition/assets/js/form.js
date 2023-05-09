// Get a reference to the scrollbar element
window.innerHeight= 2000;

const scrollbar = document.querySelector('::-webkit-scrollbar');

// Listen for scroll events on the window
window.addEventListener('scroll', () => {
  // Calculate the percentage of the page that has been scrolled
  const scrolled = window.scrollY / (document.body.scrollHeight - window.innerHeight);

  // Set the width of the scrollbar thumb based on the scroll position
  scrollbar.style.setProperty('--scrollbar-thumb-width', `${scrolled * 100}%`);
});

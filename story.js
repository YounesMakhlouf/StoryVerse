// Get the main section
const mainSection = document.getElementById("main");

// Get all the child elements of the main section
const childElements = mainSection.children;

// Loop through the child elements and add the class to each one
for (let i = 0; i < childElements.length; i++) {
  childElements[i].classList.add("sub-section");
}

// Get the main section
const mainSection = document.getElementById("main");

// Get all the child elements of the main section
const childElements = mainSection.children;

// Loop through the child elements and add the class to each one
for (let i = 0; i < childElements.length; i++) {
  childElements[i].classList.add("sub-section");
}

// select the button and the sections container
const addButton = document.getElementById("add-section");
const sectionsContainer = document.getElementById("sections");

// add a click event listener to the button
addButton.addEventListener("click", function () {
  // create a new section div
  const newSection = document.createElement("div");
  newSection.classList.add("section");

  // create a new title for the section
  const newTitle = document.createElement("h2");
  newTitle.textContent = "New Section";

  // append the title to the section
  newSection.appendChild(newTitle);

  // create a new paragraph for the section
  const newParagraph = document.createElement("p");
  newParagraph.textContent = "This is a new section added with JavaScript.";

  // append the paragraph to the section
  newSection.appendChild(newParagraph);

  // append the section to the sections container
  sectionsContainer.appendChild(newSection);
});

// Get the main section
const mainSection = document.getElementById("main");

// Get all the child elements of the main section
const childElements = mainSection.children;

// Loop through the child elements and add the class to each one
for (let i = 0; i < childElements.length; i++) {
  childElements[i].classList.add("sub-section");
}

const addSectionBtn = document.getElementById("add-section");
addSectionBtn.addEventListener("click", function () {
  const section = document.createElement("section");
  const p = document.createElement("p");
  const text = document.createTextNode("section babyyyy");
  p.appendChild(text);
  section.append(p);

  mainSection.insertBefore(section, addSectionBtn);
});

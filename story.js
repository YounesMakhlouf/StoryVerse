// functions babyyyyy
function resizetextarea(tr) {
  // reset the height to auto to allow the textarea to resize
  tr.style.height = tr.scrollHeight + "px";
}

function addsection() {
  const section = document.createElement("section");
  const sectionInput = document.createElement("textarea");
  sectionInput.setAttribute(
    "placeholder",
    "and they lived happily ever after "
  );

  const p = document.createElement("p");
  const text = document.createTextNode("section babyyyy");

  p.appendChild(text);
  section.appendChild(p);
  section.appendChild(sectionInput);
  sectionInput.classList.add("section-input");
  section.classList.add("sub-section");
  sectionInput.focus();
  sectionInput.addEventListener("input", function () {
    resizetextarea(sectionInput);
  });

  mainSection.insertBefore(section, addSectionBtn);
}

// Get the main section
const mainSection = document.getElementById("main");

// Get all the child elements of the main section
const childElements = mainSection.children;
let jsection = 1;
// Loop through the child elements and add the class to each one
for (let i = 0; i < childElements.length; i++) {
  childElements[i].classList.add("sub-section");

  if (childElements[i].nodeName == "SECTION") {
    const sectionCorner = document.createElement("div");
    sectionCorner.classList.add("section-corner");
    sectionCorner.innerHTML = jsection;
    childElements[i].append(sectionCorner);
    jsection++;
  }
}

const addSectionBtn = document.getElementById("add-section");

addSectionBtn.addEventListener("click", addsection);

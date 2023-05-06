import "../styles/Storypage.scss";
console.log("hehe2");

/* adding highlight functions*/
function addHighlightByClassName(className) {
  const allContribution = document.querySelectorAll("." + className);

  allContribution.forEach((contribution) => {
    addHighlight(contribution);
  });
}

function addHighlight(contribution) {
  contribution.addEventListener("mouseenter", function () {
    contribution.classList.add("highlight");
  });

  contribution.addEventListener("mouseleave", function () {
    contribution.classList.remove("highlight");
  });
}

/* adding the votes on click functions*/
function addVotesHider() {
  const allContribution = document.querySelectorAll(".contribution");
  allContribution.forEach((contribution) => {
    const votes = contribution.querySelector(".votes");
    const text = contribution.querySelector(".text");
    console.log("votyes");
    text.addEventListener("click", () => votes.classList.toggle("hidden"));
  });
}

// the function that makes the scrolling with the double click
function scrollingTransformer() {
  const allContributionsDivs = document.querySelectorAll(".contributions");
  allContributionsDivs.forEach((contributions) => {
    contributions.addEventListener("dblclick", () => {
      if (contributions.classList.contains("scrolling-wrapper")) {
        reformIntoOneContribution(contributions);
      } else {
        makeItWrap(contributions);
      }
    });
  });
}

wheelHorizontalScrollingToContributions();
//the functions of scrollingTransformer
function reformIntoOneContribution(contributions) {
  console.log("slKSks LS K");
  contributions.classList.remove("scrolling-wrapper");
  removeChildrenByClassName(contributions, "contribution-wrapper");

  const newMainContribution = createMainContribution(
    "skander",
    "loqslkdnqlkdnqlkndùqlknùqlskndlSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSqknlk",
    "kjslsnNSnsMS3"
  );
  contributions.appendChild(newMainContribution);
}

function makeItWrap(contributions) {
  contributions.classList.add("scrolling-wrapper");
  contributions.appendChild(
    createContributionForWrapper(
      "skander",
      "qsk,qsQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQr"
    )
  );
  contributions.appendChild(createContributionForWrapper("skander", "qsk,qsr"));
  contributions.appendChild(createContributionForWrapper("skander", "qsk,qsr"));
  for (let i = 0; i < contributions.children.length; i++) {
    contributions.children[i].classList.add("contribution-wrapper");
    contributions.children[i].classList.add("card");
    contributions.children[i].classList.remove("contribution");
  }
  console.log("make it wrap");
}

//useful functions in general
function removeChildrenByClassName(parentNode, className) {
  const childs = parentNode.getElementsByClassName(className);
  let i = 0;
  while (childs.length > 0) {
    childs[0].remove();
    console.log(i);
    i++;
    if (i > 100) {
      break;
    }
  }
}
console.log("hehe");

//mouse scrolling functions
function wheelHorizontalScrollingToContributions() {
  const allContributionsDivs = document.querySelectorAll(".contributions");

  allContributionsDivs.forEach((contributions) =>
    addHorizontalWheelScrolling(contributions)
  );
}

function addHorizontalWheelScrolling(element) {
  element.addEventListener("wheel", function (event) {
    event.preventDefault();
    element.scrollLeft += event.deltaY * 0.5;
  });
}

addHighlightByClassName("contribution");
addVotesHider();
scrollingTransformer();

// const addbuttons = document.querySelectorAll(".fas"); // takes all the active + addbuttons

// addbuttons.forEach((addbutton) => {
//   addbutton.addEventListener("click", addEmptyContribution); // makes the button add empty cars
// });

// function addEmptyContribution(e) {
//   const button = e.target;
//   const buttondiv = button.parentNode;

//   const Contributions = button.parentNode.parentNode; // selectionne l'ul qui a tout les div
//   emptyContribution = document.createElement("div");
//   emptyContribution.classList.add("empty-contribution");
//   emptyContribution.innerHTML = emptyContributionhtml();
//   Contributions.insertBefore(emptyContribution, buttondiv);
//   let inputArea = emptyContribution.querySelector(".input-area");
//   inputArea.addEventListener("input", function () {
//     resizetextarea(inputArea);
//   });

//   const saveButton = emptyContribution.querySelector(".save-btn");
//   saveButton.addEventListener("click", function () {
//     addContribution(Contributions, emptyContribution);
//   });
//   buttondiv.remove();
// }

// function addContribution(Contributions, emptyContribution) {
//   const newContribution = document.createElement("div");
//   newContribution.classList.add("contribution");
//   addHighlight(newContribution);
//   let input = emptyContribution.querySelector(".input-area").value.trim(); //takes the story someone added
//   newContribution.innerHTML = getContributionTemplate(input);
//   Contributions.append(newContribution);
//   emptyContribution.remove();
// }

// reset the height to auto to allow the textarea to resize
function resizetextarea(tr) {
  tr.style.height = tr.scrollHeight + "px";
}

// function emptyContributionhtml() {
//   return `

//         <textarea class="input-area"

//           placeholder="Enter your newContribution here"
//         ></textarea>

//       <div class="card-footer">
//         <button class="btn btn-primary save-btn">Save</button>
//       </div>
//     `;
// }

function getContributionTemplate(inputValue) {
  return `<div class="contribution">${inputValue}</div>`;
}

function createMainContribution(name, text, votes) {
  const contribution = document.createElement("div");
  contribution.classList.add("contribution", "row");

  const nameDiv = document.createElement("div");
  nameDiv.classList.add("col-2", "name");

  const nameHeading = document.createElement("h3");
  nameHeading.textContent = name;

  nameDiv.appendChild(nameHeading);

  const textDiv = document.createElement("div");
  textDiv.classList.add("text", "col-9");

  const textParagraph = document.createElement("p");
  textParagraph.textContent = text;

  textDiv.appendChild(textParagraph);

  const votesDiv = document.createElement("div");
  votesDiv.classList.add("votes", "col-1", "hidden");

  const upvoteButton = document.createElement("button");
  upvoteButton.classList.add("upvote");
  upvoteButton.textContent = "Upvote";

  const numberVotesParagraph = document.createElement("p");
  numberVotesParagraph.classList.add("number-votes");
  numberVotesParagraph.textContent = votes;

  const downvoteButton = document.createElement("button");
  downvoteButton.classList.add("downvote");
  downvoteButton.textContent = "Downvote";

  votesDiv.appendChild(upvoteButton);
  votesDiv.appendChild(numberVotesParagraph);
  votesDiv.appendChild(downvoteButton);

  contribution.appendChild(nameDiv);
  contribution.appendChild(textDiv);
  contribution.appendChild(votesDiv);
  addHighlight(contribution);

  return contribution;
}
function createContributionForWrapper(name, paragraph) {
  const contribution = document.createElement("div");
  contribution.classList.add("contribution-wrapper");

  const nameDiv = document.createElement("div");
  nameDiv.classList.add("name");

  const nameHeader = document.createElement("h3");
  nameHeader.textContent = name;

  nameDiv.appendChild(nameHeader);

  const textDiv = document.createElement("div");
  textDiv.classList.add("text");

  const paragraphP = document.createElement("p");
  paragraphP.textContent = paragraph;

  textDiv.appendChild(paragraphP);

  contribution.appendChild(nameDiv);
  contribution.appendChild(textDiv);

  /* with the highlighter*/
  addHighlight(contribution);

  return contribution;
}

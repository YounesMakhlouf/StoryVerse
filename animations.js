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

addHighlightByClassName("contribution");
/*addVotesHider();*/
scrollingTransformer();

/* adding highlight functions*/
function addHighlightByClassName(classname) {
  const allContribution = document.querySelectorAll("." + classname);

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

function scrollingTransformer() {
  const allContributionsDivs = document.querySelectorAll(".contributions");
  allContributionsDivs.forEach((contributions) => {
    contributions.addEventListener("dblclick", () => {
      if (contributions.classList.contains("scrolling-wrapper")) {
        /*const allContributionsThatScroll =
          contributions.querySelectorAll(".contribution");*/

        reformIntoOneContribution(contributions);
      } else {
        makeItWrap(contributions);
      }
    });
  });
}

addHighlightByClassName("contribution");
/*addVotesHider();*/
scrollingTransformer();

function reformIntoOneContribution(contributions) {
  contributions.classList.remove("scrolling-wrapper");
  for (let i = 0; i < contributions.children.length; i++) {
    contributions.children[i].remove();
  }

  contributions.appendChild(
    createMainContribution(
      "skander",
      "loqslkdnqlkdnqlkndùqlknùqlskndlqknlk",
      "kjslsnNSnsMS3"
    )
  );
}
function makeItWrap(contributions) {
  contributions.classList.add("scrolling-wrapper");
  contributions.appendChild(createContributionForWrapper("skander", "qsk,qsr"));
  contributions.appendChild(createContributionForWrapper("skander", "qsk,qsr"));
  contributions.appendChild(createContributionForWrapper("skander", "qsk,qsr"));
  for (let i = 0; i < contributions.children.length; i++) {
    contributions.children[i].classList.add("card");
    console.log("FSKJ QFJZMOAZOMNZ%OZA%OJp");
  }
}

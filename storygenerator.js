/* const cards = document.querySelectorAll(".card");

cards.forEach((card) => {
  card.addEventListener("mouseover", function () {
    let theChildren = Array.from(card.children);
    theChildren.forEach((child) => {
      child.classList.toggle("hidden");
    });
  });
});

cards.forEach((card) => {
  card.addEventListener("mouseout", function () {
    let theChildren = Array.from(card.children);
    theChildren.forEach((child) => {
        child.classList.toggle("hidden");
    });
  });
});
*/

const addbuttons = document.querySelectorAll(".fas"); // takes all the active + addbuttons

addbuttons.forEach((addbutton) => {
  addbutton.addEventListener("click", addEmptyContribution); // makes the button add empty cars
});

function addEmptyContribution(e) {
  const button = e.target;
  const buttondiv = button.parentNode;

  const Contributions = button.parentNode.parentNode; // selectionne l'ul qui a tout les div
  emptyContribution = document.createElement("div");
  emptyContribution.classList.add("empty-contribution");
  emptyContribution.innerHTML = emptyContributionhtml();
  Contributions.insertBefore(emptyContribution, buttondiv);
  let inputArea = emptyContribution.querySelector(".input-area");
  inputArea.addEventListener("input", function () {
    resizetextarea(inputArea);
  });

  const saveButton = emptyContribution.querySelector(".save-btn");
  saveButton.addEventListener("click", function () {
    addContribution(Contributions, emptyContribution);
  });
  buttondiv.remove();
}

function addContribution(Contributions, emptyContribution) {
  const newContribution = document.createElement("div");
  newContribution.classList.add("contribution");
  addHighlight(newContribution);
  let input = emptyContribution.querySelector(".input-area").value.trim(); //takes the story someone added
  newContribution.innerHTML = getContributionTemplate(input);
  Contributions.append(newContribution);
  emptyContribution.remove();
}

// reset the height to auto to allow the textarea to resize
function resizetextarea(tr) {
  tr.style.height = tr.scrollHeight + "px";
}

function emptyContributionhtml() {
  return `
      
        <textarea class="input-area"
          
          placeholder="Enter your newContribution here"
        ></textarea>
      
      <div class="card-footer">
        <button class="btn btn-primary save-btn">Save</button>
      </div>
    `;
}

function getContributionTemplate(inputValue) {
  return `<div class="contribution">${inputValue}</div>`;
}

/*   // Create a new list item (card)
   const card = document.createElement("li");
   card.classList.add("card");

   // Create the card body and append it to the card
   const cardBody = document.createElement("div");
   cardBody.classList.add("card-body");
   card.appendChild(cardBody);

   // Create the textarea and append it to the card body
   const textarea = document.createElement("textarea");
   textarea.classList.add("card-text");
   textarea.placeholder = "Enter your contribution here";
   cardBody.appendChild(textarea);

   // Create the card footer and append it to the card
   const cardFooter = document.createElement("div");
   cardFooter.classList.add("card-footer");
   card.appendChild(cardFooter);

   // Create the save button and append it to the card footer
   const saveBtn = document.createElement("button");
   saveBtn.classList.add("btn", "btn-primary", "save-btn");
   saveBtn.textContent = "Save";
   cardFooter.appendChild(saveBtn);

   // Insert the new card before the add-card button
   list.insertBefore(card, addCardBtn);*/
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

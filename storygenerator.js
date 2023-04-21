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
  addbutton.addEventListener("click", addEmptyCard); // makes the button add empty cars
});

function addEmptyCard(e) {
  const button = e.target;

  const list = button.parentNode.parentNode.parentNode; // selectionne l'ul qui a tout les div
  const li = button.parentNode.parentNode;
  const emptycard = document.createElement("li");
  emptycard.classList.add("card", "empty-card");
  emptycard.innerHTML = emptycardhtml();
  list.insertBefore(emptycard, li);
  let input = emptycard.children[0].children[0];
  input.addEventListener("input", function () {
    resizetextarea(input);
  });

  emptycard.children[1].children[0].addEventListener("click", function () {
    addContribution(list, emptycard);
  });
  li.remove();
}

function addContribution(list, emptycard) {
  const contribution = document.createElement("li");
  contribution.classList.add("card");
  let input = emptycard.children[0].children[0].value.trim(); //takes the story someone added
  contribution.innerHTML = getContributionTemplate(input);
  list.append(contribution);
  emptycard.remove();
}

// reset the height to auto to allow the textarea to resize
function resizetextarea(tr) {
  tr.style.height = tr.scrollHeight + "px";
}

function emptycardhtml() {
  return `
      <div class="card-body">
        <textarea
          class="card-text"
          placeholder="Enter your contribution here"
        ></textarea>
      </div>
      <div class="card-footer">
        <button class="btn btn-primary save-btn">Save</button>
      </div>
    `;
}

function getContributionTemplate(inputValue) {
  return `<div class="card-header">
                <h4 class="card-title">9atous</h4>
                <button class="btn btn-follow">Follow</button>
            </div>
            <div class="card-body">
                <p class="card-text">${inputValue}</p>
            </div>
            <div class="card-footer">
                <div class="post-vote-buttons">
                    <div class="upvote"></div>
                    <div class="downvote"></div>
                </div>
            </div>`;
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

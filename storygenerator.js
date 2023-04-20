const saveBtns = document.querySelectorAll(".save-btn");

// Add event listener to each save button
saveBtns.forEach(saveBtn => {
    saveBtn.addEventListener("click", addContribution);
});

function addContribution(e) {
    const button = e.target;
    const list = button.parentNode.parentNode.parentNode;
      const li = button.parentNode.parentNode;
  const input = li.querySelector(".card-text").value.trim();

    let contribution = document.createElement("li");
    contribution.className = "card";
    contribution.innerHTML = `<div class="card-header">
                      <h4 class="card-title">9atous</h4>
                      <button class="btn btn-follow">Follow</button>
                    </div>
                    <div class="card-body">
                      <p class="card-text">${input}</p>
                    </div>
                    <div class="card-footer">
                      <div class="post-vote-buttons">
                        <div class="upvote"></div>
                        <div class="downvote"></div>
                      </div>
                    </div>`;
    list.insertBefore(contribution, button.parentNode.parentNode);
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
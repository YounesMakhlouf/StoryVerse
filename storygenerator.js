// Get the list and the add-card button
var list = document.getElementById("card-list");
var addCardBtn = document.querySelector(".add-card");

// Add event listener to the add-card button
addCardBtn.addEventListener("click", function() {
    // Create a new list item (card)
    var card = document.createElement("li");
    card.classList.add("card");

    // Create the card body and append it to the card
    var cardBody = document.createElement("div");
    cardBody.classList.add("card-body");
    card.appendChild(cardBody);

    // Create the textarea and append it to the card body
    var textarea = document.createElement("textarea");
    textarea.classList.add("card-text");
    textarea.placeholder = "Enter your contribution here";
    cardBody.appendChild(textarea);

    // Create the card footer and append it to the card
    var cardFooter = document.createElement("div");
    cardFooter.classList.add("card-footer");
    card.appendChild(cardFooter);

    // Create the save button and append it to the card footer
    var saveBtn = document.createElement("button");
    saveBtn.classList.add("btn", "btn-primary", "save-btn");
    saveBtn.textContent = "Save";
    cardFooter.appendChild(saveBtn);

    // Insert the new card before the add-card button
    list.insertBefore(card, addCardBtn);
});

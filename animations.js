const cards = document.querySelectorAll(".card");
console.log("qssjqdjq!:");

cards.forEach((card) => {
  card.addEventListener("mouseover", function () {
    card.classList.toggle("highlight");
  });
});

cards.forEach((card) => {
  card.addEventListener("mouseout", function () {
    card.classList.toggle("highlight");
  });
});

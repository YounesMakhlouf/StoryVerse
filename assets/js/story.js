import '../styles/story.scss';

// Code pour récupérer les suggestions des autres utilisateurs (supposons que les suggestions sont stockées dans un tableau appelé 'suggestions')
const suggestions = [
    {   name:"Salma",
        suggestion: "Il était une fois un jeune garçon nommé Tom qui rêvait de devenir un grand pianiste. Chaque jour, il s'entraînait dur sur son piano, espérant un jour réaliser son rêve.",
        likes: 7,
        liked: false
    },
    {
        name:"Wided",
        suggestion: "La nuit était tombée sur la ville, et Marie se dirigeait seule vers sa voiture après une longue journée de travail. Soudain, elle entendit des pas derrière elle et se retourna pour voir une silhouette sombre s'approcher.",
        likes: 5,
        liked: false
    },
    {
        name:"Younes",
        suggestion: "La petite fleur bleue avait grandi seule au bord de la route, négligée et ignorée par tous ceux qui passaient. Mais un jour, un enfant s'arrêta pour la regarder de plus près, et il vit toute la beauté et la grâce qu'elle avait à offrir.",
        likes: 7,
        liked: false
    }
];

// Fonction pour trier les suggestions par nombre de likes décroissant
function sortSuggestions() {
    suggestions.sort(function(a, b) {
        return b.likes - a.likes;
    });
}

// Fonction pour mettre à jour la liste de suggestions affichées sur la page
function updateSuggestions() {
    const suggestionList = document.getElementById('suggestion-list');
    // Vide la liste actuelle
    suggestionList.innerHTML = '';

    // Tri des suggestions
    sortSuggestions();

    // Ajout des suggestions triées à la liste
    for (const suggestion of suggestions) {
        
        const suggestionItem = document.createElement('div');
        suggestionItem.classList.add('suggestion-item');
        suggestionItem.innerHTML = `
             <button class="like-button"><i class="${suggestion.liked ? 'fas' : 'far'} fa-heart">
            <span class="like-count">${suggestion.likes}</span></i></button>         
            <strong>${suggestion.name}:&nbsp&nbsp </strong> ${suggestion.suggestion}
            
        `;

        suggestionList.appendChild(suggestionItem);

        const likeButton = suggestionItem.getElementsByClassName('like-button')[0];
        likeButton.addEventListener('click', function() {
            // Code pour gérer le bouton Like
            if (!suggestion.liked) {
                suggestion.likes++;
                suggestion.liked = true;
                suggestionItem.getElementsByClassName('like-count')[0].textContent = suggestion.likes;
                likeButton.innerHTML = '<i class="fas fa-heart"></i>';
                likeButton.classList.add('liked');
                console.log(`Like pour la suggestion : ${suggestion.suggestion}`);
            } else {
                suggestion.likes--;
                suggestion.liked = false;
                suggestionItem.getElementsByClassName('like-count')[0].textContent = suggestion.likes;
                likeButton.innerHTML = '<i class="far fa-heart"></i>';
                likeButton.classList.remove('liked');
                console.log(`Unlike pour la suggestion : ${suggestion.suggestion}`);
            }
            
            // Mettre à jour la liste de suggestions après chaque clic sur le bouton Like
            updateSuggestions();
        });
    }
}

// Appel initial pour afficher les suggestions sur la page
updateSuggestions();

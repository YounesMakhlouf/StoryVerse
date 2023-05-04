// Récupérer tous les boutons "Follow"
let followButtons = document.querySelectorAll('.follow-button');

// Fonction réutilisable pour mettre à jour le texte du bouton "Follow"
function updateFollowButtonText(button, isFollowing) {
    if (isFollowing) {
        button.textContent = 'Unfollow';
    } else {
        button.textContent = 'Follow';
    }
}

// Fonction réutilisable pour mettre à jour tous les boutons "Follow"
function updateAllFollowButtons(isFollowing) {
    followButtons.forEach(function(button) {
        updateFollowButtonText(button, isFollowing);
    });
}

// Ajouter un événement "click" à chaque bouton "Follow"
followButtons.forEach(function(button) {
    let userId = button.getAttribute('data-user-id');
    let followingUrl = button.getAttribute('data-following');

    // Fonction pour envoyer une requête POST au serveur
    function sendFollowRequest() {
        axios.post(followingUrl)
            .then(function(response) {
                let isFollowing = response.data.isFollowing;
                updateAllFollowButtons(isFollowing);
            })
            .catch(function(error) {
                console.log(error);
            });
    }

    // Ajouter un événement "click" au bouton "Follow"
    button.addEventListener('click', function() {
        sendFollowRequest();
    });
});


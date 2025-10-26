// Fonction utilitaire : affiche une alerte personnalisée
function showWelcomeMessage(message) {
    alert(message);
}

function initAccueilPage() {
    console.log("Page d'accueil chargée.");
    const numberElement = document.getElementById("lucky-number");
    if (numberElement) {
        // Récupère la valeur du nombre depuis le texte
        const numberText = numberElement.textContent || numberElement.innerText;
        const number = parseInt(numberText.replace(/\D/g, ''), 10); // supprime tout sauf chiffres
        if (!isNaN(number) && number >= 50) {
            numberElement.style.color = "blue";
            numberElement.style.fontWeight = "bold";
        }
    }
}

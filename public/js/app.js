// Fonction pour basculer la visibilité du menu mobile
function toggleMobileMenu() {
    const menu = document.querySelector('#mobile-menu');
    menu.classList.toggle('hidden');
}

// Ajouter un écouteur d'événements au bouton du menu mobile
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuButton = document.querySelector('#mobile-menu-button');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', toggleMobileMenu);
    }
});

// Fonction pour afficher une notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.className = `fixed bottom-4 right-4 px-4 py-2 rounded-md text-white ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Exemple d'utilisation de la notification
// showNotification('Projet créé avec succès !', 'success');
// showNotification('Une erreur est survenue.', 'error');


// Désactiver le clic droit
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    alert('Le clic droit est désactivé !');
});

// Désactiver les raccourcis clavier (F12, Ctrl+Shift+I, Ctrl+U)
document.addEventListener('keydown', function(e) {
    // Désactiver F12 (inspecteur de code)
    if (e.keyCode === 123) { 
        e.preventDefault();
        alert('L\'inspector de code est désactivé !');
    }
    // Désactiver Ctrl+Shift+I (inspecteur de code)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
        e.preventDefault();
        alert('L\'inspecteur de code est désactivé !');
    }
    // Désactiver Ctrl+U (affichage du code source)
    if (e.ctrlKey && e.keyCode === 85) {
        e.preventDefault();
        alert('L\'affichage du code source est désactivé !');
    }
});

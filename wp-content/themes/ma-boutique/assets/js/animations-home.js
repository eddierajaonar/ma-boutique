/**
 * Animations de la page d'accueil (front-page.php).
 * Chargé seulement sur l'accueil : voir is_front_page() dans functions.php.
 */
document.addEventListener('DOMContentLoaded', () => {
	// Le titre apparaît en montant.
	if (document.querySelector('.hero')) {
		gsap.from('.hero h1', { y: 40, opacity: 0, duration: 1, ease: 'power2.out' });
	}
});

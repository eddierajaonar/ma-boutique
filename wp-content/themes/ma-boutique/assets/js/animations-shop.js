/**
 * Animations de la boutique (/shop/ et catégories de produits).
 * Chargé seulement sur ces pages : voir is_shop() / is_product_taxonomy() dans functions.php.
 */
document.addEventListener('DOMContentLoaded', () => {
	gsap.registerPlugin(ScrollTrigger);

	// Chaque produit apparaît quand on scrolle jusqu'à lui.
	gsap.utils.toArray('ul.products li.product').forEach((produit) => {
		gsap.from(produit, {
			y: 60,
			opacity: 0,
			duration: 0.8,
			ease: 'power2.out',
			scrollTrigger: { trigger: produit, start: 'top 85%' },
		});
	});
});

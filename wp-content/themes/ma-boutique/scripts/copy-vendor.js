/**
 * Copie les librairies JS installées par npm (node_modules/) vers assets/js/vendor/,
 * pour que WordPress puisse les charger avec wp_enqueue_script().
 * Lancé automatiquement après "npm install" (script "postinstall" de package.json),
 * ou à la main avec : npm run vendor
 */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..');
const destination = path.join(root, 'assets/js/vendor');

// [fichier dans node_modules, nom dans assets/js/vendor]
const files = [
	['gsap/dist/gsap.min.js', 'gsap.min.js'],
	['gsap/dist/ScrollTrigger.min.js', 'ScrollTrigger.min.js'],
];

fs.mkdirSync(destination, { recursive: true });

for (const [source, name] of files) {
	fs.copyFileSync(path.join(root, 'node_modules', source), path.join(destination, name));
	console.log(`copié : ${source} -> assets/js/vendor/${name}`);
}

# Ma Boutique

Boutique WooCommerce pour un restaurant (pizzas, accompagnements), avec un **thème WordPress 100 % custom** écrit de zéro.

- **WordPress** + **WooCommerce**, lancés avec **Docker** (MariaDB, WordPress/Apache PHP 8.3, phpMyAdmin)
- Thème classique en PHP : `wp-content/themes/ma-boutique/`
- **Tailwind CSS v4** + **daisyUI 5** pour le style, **GSAP** (+ ScrollTrigger) pour les animations

> Projet d'apprentissage : les explications détaillées (hiérarchie des templates, surcharge WooCommerce, hooks, Tailwind, GSAP…) se trouvent dans [`Formation_theme_woocommerce.txt`](Formation_theme_woocommerce.txt).

---

## Prérequis

À installer une seule fois sur l'ordinateur :

| Outil | Vérifier avec |
|---|---|
| [Git](https://git-scm.com/) | `git --version` |
| [Docker](https://docs.docker.com/get-docker/) + Docker Compose | `docker --version` et `docker compose version` |
| [Node.js](https://nodejs.org/) (version LTS, npm est inclus) | `node -v` et `npm -v` |

---

## Installation sur un nouvel ordinateur

### 1. Récupérer le projet

```bash
git clone <URL_DU_DEPOT> ma-boutique
cd ma-boutique
```

### 2. Créer le fichier `.env` (mots de passe de la base de données)

Le fichier `.env` n'est pas dans git (secrets). On part de l'exemple :

```bash
cp .env.example .env
```

Puis ouvrir `.env` et remplacer les `change_me_...` par de vrais mots de passe.

### 3. Installer les outils du thème (Tailwind + GSAP)

```bash
cd wp-content/themes/ma-boutique
npm ci
cd ../../..
```

`npm ci` installe les versions exactes de `package-lock.json`, puis lance automatiquement (script `postinstall`) :
- `npm run vendor` : copie GSAP dans `assets/js/vendor/`
- `npm run build` : compile Tailwind dans `assets/css/main.css`

### 4. Donner les droits d'écriture au conteneur sur `wp-content`

WordPress (utilisateur `www-data` dans le conteneur) doit pouvoir écrire dans `wp-content` (plugins, images…). En local uniquement :

```bash
chmod -R 777 wp-content
```

> Plus de détails dans [`Note_importante.txt`](Note_importante.txt).

### 5. Démarrer les conteneurs

```bash
docker compose up -d
```

| Service | Adresse |
|---|---|
| Site | http://localhost:8080/ |
| phpMyAdmin | http://127.0.0.1:8082/ |

### 6. Configurer WordPress (première fois seulement)

Git ne contient **que le code** : la base de données (produits, pages, réglages) et les images (`wp-content/uploads/`) ne sont pas dans le dépôt. Sur une base neuve :

1. Ouvrir http://localhost:8080/ et suivre l'installation de WordPress (langue, titre, compte admin).
2. **Extensions → Ajouter** : installer et activer **WooCommerce**.
3. **Apparence → Thèmes** : activer **ma-boutique**.
4. **Réglages → Permaliens** : choisir **Titre de la publication** (`/%postname%/`).
5. **Produits → Importer** : importer `pizzas_woocommerce.csv` puis `accompaniements_woocommerce.csv`.
6. **Apparence → Personnaliser → Identité du site** : ajouter le logo.
7. **WooCommerce → Réglages → Visibilité du site** : passer en **En ligne**, sinon les visiteurs non connectés voient la page « Bientôt disponible » à la place du thème.

> Pour retrouver exactement les mêmes données qu'un autre PC : exporter la base depuis phpMyAdmin (**Exporter**) et copier le dossier `wp-content/uploads/`, puis les importer sur le nouveau PC.

---

## Développement au quotidien

```bash
docker compose up -d                 # démarrer le site
cd wp-content/themes/ma-boutique
npm run watch                        # recompile Tailwind à chaque sauvegarde (Ctrl+C pour arrêter)
```

Puis recharger la page avec **Ctrl+F5**.

Après un `git pull` qui modifie `package.json` / `package-lock.json` :

```bash
cd wp-content/themes/ma-boutique
npm ci
```

Arrêter le site :

```bash
docker compose down                  # les données de la base sont conservées (volume db_data)
```

### Commandes npm (dans `wp-content/themes/ma-boutique`)

| Commande | Rôle |
|---|---|
| `npm ci` | Installer tout (nouveau PC, après un `git pull`) |
| `npm install <paquet>` | Ajouter une librairie |
| `npm run watch` | Compiler Tailwind en continu pendant le développement |
| `npm run build` | Compiler Tailwind minifié (avant une mise en ligne) |
| `npm run vendor` | Recopier GSAP de `node_modules/` vers `assets/js/vendor/` |

---

## Structure du thème

```
wp-content/themes/ma-boutique/
├── style.css               en-tête du thème (obligatoire) + CSS perso
├── functions.php           supports du thème, chargement CSS/JS (par page)
├── header.php / footer.php wp_head() / wp_footer() : indispensables
├── index.php               template de secours
├── front-page.php          page d'accueil
├── src/input.css           source Tailwind (à éditer)
├── assets/
│   ├── css/main.css        CSS Tailwind GÉNÉRÉ (pas dans git)
│   └── js/
│       ├── vendor/         GSAP copié depuis node_modules (pas dans git)
│       ├── animations-home.js   chargé seulement sur l'accueil
│       └── animations-shop.js   chargé seulement sur la boutique
├── scripts/copy-vendor.js  copie GSAP vers assets/js/vendor/
└── package.json            outils et scripts npm
```

Pour modifier un template WooCommerce : le copier depuis `wp-content/plugins/woocommerce/templates/` vers `wp-content/themes/ma-boutique/woocommerce/` (même chemin), ou mieux, utiliser un hook dans `functions.php`. Ne jamais modifier les fichiers du plugin.

---

## Dépannage

| Problème | Solution |
|---|---|
| Site sans style, ou `gsap is not defined` dans la console | `npm ci` n'a pas été lancé dans le thème |
| Une classe Tailwind ne fonctionne pas | `npm run watch` ne tourne pas, puis Ctrl+F5 |
| La page d'accueil affiche « Bientôt disponible » | Se connecter en admin, ou passer la boutique **En ligne** (étape 6.7) |
| WordPress demande des identifiants FTP pour installer un plugin | Droits sur `wp-content` (étape 4) |
| `npm audit` signale des vulnérabilités (`braces`) | Concerne uniquement l'outil Tailwind en local. **Ne pas** lancer `npm audit fix --force` (il rétrograde Tailwind) |

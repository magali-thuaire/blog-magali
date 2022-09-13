# Blog Magali
*Blog Professionnel PHP (POO et Design Patterns)*

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/17359770e1184cfab6bf0ff01b210f21)](https://www.codacy.com/gh/magali-thuaire/blog-magali/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=magali-thuaire/blog-magali&amp;utm_campaign=Badge_Grade)

## Compétences

-	Conceptualiser l'ensemble de son application en décrivant sa structure (Entités / Domain Objects)
-	Créer et maintenir l’architecture technique d’un site
-	Proposer un code propre et facilement évolutif 
-	Assurer le suivi qualité́ d’un projet

## Setup

**Get the git Repository**

Clone over SSH

```
git clone git@github.com:magali-thuaire/blog-magali.git 
```

Clone over HTTPS

```
git clone https://github.com/magali-thuaire/blog-magali.git
```


**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

**Database Setup**

Open your MySQL database manager and execute the *MySQL script* to build the database :

```
diagrams/PDM_script.sql
```

Configure the <span style="color:green">**_config/_database.php**</span> file. Open the file and make any adjustments you need.

```
db_name, db_host, db_user, db_pass
```

**Email Setup**

Configure the <span style="color:green">**_config/_email.php**</span> file. Open the file and make any adjustments you need.

```
URL_SITE, EMAIL_DEFAULT_TO, EMAIL_DEFAULT_FROM
```

**Server Setup**

Configure your <span style="color:green">**php.ini**</span> file.

```
date.timezone = "Europe/Paris"
```
**Webpack Assets**

This app uses Webpack for the CSS, JS and image files. The final built assets are already inside the
project.

If you *do* want to build the Webpack assets manually, make sure you have [yarn](https://yarnpkg.com/lang/en/)
installed and then run:

```
yarn install
yarn dev --watch
```

## Default Admin Connexion
```
login : superadmin@blog.fr
password : test
```

## Structure
```
.
├── _config
├── app
├── assets
├── core
├── public
├── README.md
├── composer.json
├── composer.lock
├── package.json
├── webpack.config.js
└── yarn.lock
```

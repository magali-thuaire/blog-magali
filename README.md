# Blog de Magali

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/17359770e1184cfab6bf0ff01b210f21)](https://www.codacy.com/gh/magali-thuaire/blog-magali/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=magali-thuaire/blog-magali&amp;utm_campaign=Badge_Grade)

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

**Webpack Assets**

This app uses Webpack for the CSS, JS and image files. The final built assets are already inside the
project.

If you *do* want to build the Webpack assets manually, make sure you have [yarn](https://yarnpkg.com/lang/en/)
installed and then run:

```
yarn install
yarn dev --watch
```

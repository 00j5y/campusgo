<img src="https://imgur.com/eeOyqG5.jpeg" alt="CampusGo">
<h2 align="center">🔍 Sommaire</h2>

- [📋 Exigence](#-exigence)
- [✨ Récupération du projet](#-récupération-du-projet)
- [▶️ Lancer le projet](#-lancer-le-projet)

<h2 align="center">📋 Exigence</h2>

- NodeJS (https://nodejs.org/dist/v24.11.1/node-v24.11.1-x64.msi)
- PHP (https://windows.php.net/download/)
- Composer (https://getcomposer.org/download/)

<h2 align="center">✨ Récupération du projet</h2>

### 1- Lancer un CMD dans un dossier
<img src="https://imgur.com/ERcae1L.gif" alt="Bannière" width="50%">

### 2- Cloner le code
```bash
git clone https://github.com/00j5y/campusgo.git
```

### 3- Aller dans le dossier du projet
```bash
cd campusgo
```

### 4- Exécuter la commande pour avoir les dépendances front-end
```bash
npm install
```

### 5- Exécuter la commande pour avoir les dépendances PHP
```bash
composer install
```

### 6- Migrer la BD de Laragon dans le projet Laravel
```bash
php artisan migrate
```

### 7- Mettre les info de DB dans le .env
```bash
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### 7- Mettre file pour le cache dans le .env
```bash
CACHE_STORE=file
```

<h2 align="center">▶️ Lancer le projet</h2>

### 1- Lancer Vite
```bash
npm run dev
```

### 2- Lancer Laravel
```bash
php artisan serve
```

### 3- Lancer 127.0.0.1:8000 dans son navigateur

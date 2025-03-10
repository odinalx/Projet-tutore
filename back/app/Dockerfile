FROM php:8.3-cli

# Mise à jour des paquets et installation des extensions nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Définition du répertoire de travail
WORKDIR /var/app

# Copie des fichiers de l'application
COPY . .

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install 

# Exposition du port 80
EXPOSE 80

# Commande de démarrage
CMD sh -c "composer install --no-dev --optimize-autoloader && php -S 0.0.0.0:80 -t /var/app/public"


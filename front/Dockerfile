FROM node:20-alpine

WORKDIR /app

# Copier tous les fichiers
COPY . .

# Installer les dépendances
RUN npm install

# Exposer le port
EXPOSE 8080

# Commande de démarrage simplifiée
CMD ["npm", "run", "dev"] 
# Application Frontend

Application Vue 3 avec TypeScript, Tailwind CSS et shadcn-vue.

## 🚀 Technologies

- [Vue 3](https://vuejs.org/) - Framework progressif
- [TypeScript](https://www.typescriptlang.org/) - Typage statique
- [Tailwind CSS](https://tailwindcss.com/) - Framework CSS utilitaire
- [shadcn-vue](https://www.shadcn-vue.com/) - Composants UI
- [Vite](https://vitejs.dev/) - Build tool
- [Vue Router](https://router.vuejs.org/) - Routage
- [Pinia](https://pinia.vuejs.org/) - Gestion d'état

## 📁 Structure du Projet

```bash
src/
├── assets/ # Ressources statiques
├── components/
│ └── ui/ # Composants UI shadcn
├── lib/
│ ├── api/ # Client API
│ └── utils/ # Utilitaires
├── router/ # Configuration du routeur
├── stores/ # Stores Pinia
├── types/ # Types TypeScript
└── views/ # Pages de l'application
```

## 🛠️ Installation

### Installation des dépendances

```bash
npm install
```

### Lancement du serveur de développement

```bash
npm run dev
```

### Build pour la production

```bash
npm run build
```

### Linting

```bash
npm run lint
```

## 💻 Scripts Disponibles

- `npm run dev` - Lance le serveur de développement
- `npm run build` - Build pour la production
- `npm run preview` - Prévisualise le build
- `npm run lint` - Vérifie et corrige le code
- `npm run format` - Formate le code avec Prettier

## 🔧 Configuration VS Code Recommandée

Extensions recommandées :

- Vue Language Features (Volar)
- ESLint
- Prettier

## 📝 Conventions de Code

- ESLint pour le linting
- Prettier pour le formatage
- TypeScript strict mode
- Composants Vue en composition API avec `<script setup>`

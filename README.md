# Installation

```bash
# 1. Cloner le projet
git clone https://github.com/meriemsakhri/symfony-tps.git
cd symfony-tps

# 2. Installer les dépendances
composer install

# 3. Créer la base de données
php bin/console doctrine:database:create

# 4. CHOIX A - Méthode Simple (Recommandée pour les TPs)
php bin/console doctrine:schema:update --force

# OU CHOIX B - Méthode Migrations (Professionnelle)
php bin/console make:migration
php bin/console doctrine:migrations:migrate

# 5. Charger les données de démo
php bin/console doctrine:fixtures:load

# 6. Lancer le serveur
symfony server:start

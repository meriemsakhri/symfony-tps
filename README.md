# 🚀 SYMFONY PROJECT SETUP

## 📦 1️⃣ Clone the Repository
git clone https://github.com/meriemsakhri/symfony-tps.git
cd symfony-tps

## 📥 2️⃣ Install Dependencies
composer install

## 🗄️ 3️⃣ Create the Database
php bin/console doctrine:database:create

## 🛠️ 4️⃣ Set Up Database Schema

## 🔹 OPTION A — Simple Method (Recommended for TPs)
php bin/console doctrine:schema:update --force

## 🔹 OPTION B — Professional Method (Migrations)
php bin/console make:migration

php bin/console doctrine:migrations:migrate

## 5️⃣ Load Demo Data
php bin/console doctrine:fixtures:load

## 6️⃣ Start Symfony Server
symfony server:start

# 🧰 USEFUL COMMANDS

## 🔍 Show All Routes
php bin/console debug:router

## 🧹 Clear Cache
php bin/console cache:clear

## ✅ Validate Database Schema
php bin/console doctrine:schema:validate

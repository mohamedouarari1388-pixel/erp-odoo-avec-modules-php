--- ERP Odoo - Module de Gestion de Stock

-- Description

Ce projet est une interface web PHP permettant de gérer un catalogue de produits connectée à un ERP Odoo via son API JSON-RPC. Il permet d'effectuer des opérations CRUD de base sur les produits (ajout, vente, affichage).

-- Objectif pédagogique
 - Comprendre l'interaction entre une application PHP et un ERP Odoo
 - Maîtriser l'utilisation de l'API JSON-RPC d'Odoo
 - Développer une interface utilisateur simple et fonctionnelle



-- Fonctionnalités

 Fonctionnalité : Description 

 Ajout de produit :Formulaire pour ajouter un produit (nom, catégorie, prix, quantité) 
 Vente de produit :Bouton pour diminuer le stock de 1 unité 
 Liste des produits : Affichage de tous les produits avec leurs informations
 Design moderne :Interface élégante (dégradé bleu nuit + doré) 
 Responsive : Adapté aux écrans mobiles et desktop 



-- Technologies utilisées

 -Technologie  :  Rôle 

PHP 7.4+ : Backend et appels API 
Odoo 17 : ERP (gestion des données produits) 
JSON-RPC: Protocole de communication 
cURL: Requêtes HTTP en PHP 
HTML5 / CSS3: Interface utilisateur 


--Utilisation
Ajouter un produit
Remplir le formulaire (nom, catégorie, prix, quantité)

Cliquer sur "Envoyer"

Le produit apparaît dans la liste

Vendre un produit
Dans la liste des produits

Cliquer sur "Vendre -1" à côté du produit souhaité

Le stock diminue automatiquement

---Améliorations possibles:
  Ajouter une confirmation avant suppression

  Ajouter une fonction de recherche

  Ajouter une pagination pour la liste

  Exporter la liste en PDF/Excel

  Ajouter des graphiques statistiques

  Gestion des utilisateurs et connexion



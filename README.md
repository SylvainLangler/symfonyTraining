# Installer Symfony

Télécharger symfony ici: [https://symfony.com/download](https://symfony.com/download)

Créer le projet symfony avec la commande 

`symfony new --full my_project`

Lancer le serveur 

`php -S 127.0.0.1:8000 -t public/`

# Informations utiles

Pour faire fonctionner Emmet avec twig

`Ctrl + Shift + P`

Saisir Settings, ouvrir le JSON et ajouter
`"emmet.includeLanguages": {
        "twig": "html"
}`

# Les controllers

Créer un controller:

`php bin/console create:controller`
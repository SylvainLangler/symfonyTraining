# Installer Symfony

Télécharger symfony ici: [https://symfony.com/download](https://symfony.com/download)

Créer le projet symfony avec la commande 

`symfony new --full my_project`

Lancer le serveur 

`php -S 127.0.0.1:8000 -t public/`

ou

`symfony serve`

# Informations utiles

Tutoriel officiel Symfony 5: [https://symfonycasts.com/screencast/symfony/setup](https://symfonycasts.com/screencast/symfony/setup)

Pour faire fonctionner Emmet avec twig

`Ctrl + Shift + P`

Saisir Settings, ouvrir le JSON et ajouter
`"emmet.includeLanguages": {
        "twig": "html"
}`

Thèmes bootstrap: [https://bootswatch.com/](https://bootswatch.com/)

### Changelog Symfony 4 to 5

- un composant String pour les objets concernant du texte

- un composant Notifier qui permet d’envoyer des notifications sur plusieurs supports

- suppression du code déprécié

- amélioration des composants existants, des fonctionnalités déjà présentes

Les plus gros changements n’ont finalement pas tant porté sur les fonctionnalités, mais plutôt sur la façon de penser les mises à jour et la gestion des versions.

# Arborescence des fichiers Symfony

```bash
├── bin `Contient la console de symfony`
├── config `Fichiers de config`
├── public `Dossier dans lequel pointe le serveur web`
├── src 
│   ├── Controller 
│   ├── DataFixtures `Dossier des fixtures, fausses données générées pour la bdd`
│   ├── Entity
│   ├── Migrations
│   ├── Repository `Dossiers des repositories, utilisés pour faire le lien avec la base de données`
├── templates
│    ├── Autres dossiers de templates
│    ├── base.html.twig `Layout utilisé pour toutes les pages du site`
├── tests
├── translations
├── var
├── vendor
├── .env
├── .env.test
├── composer.json
└── ...
```
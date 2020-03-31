# Installer Symfony

Télécharger symfony ici: [https://symfony.com/download](https://symfony.com/download)

Créer le projet symfony avec la commande 

`symfony new --full my_project`

Lancer le serveur 

`php -S 127.0.0.1:8000 -t public/`

ou

`symfony serve`

## Pour installer ce projet

### `git clone https://github.com/SylvainLangler/symfonyTraining.git`

### `cd symfonyTraining`

### Vérifier le .env pour la base de données et potentielles configurations

### `composer install`

### `php bin/console doctrine:migrations:migrate`

### `php bin/console doctrine:fixtures:load`

### `symfony serve`

# Informations utiles

Tutoriel officiel Symfony 5: [https://symfonycasts.com/screencast/symfony/setup](https://symfonycasts.com/screencast/symfony/setup)

Pour faire fonctionner Emmet avec twig

`Ctrl + Shift + P`

Saisir Settings, ouvrir le JSON et ajouter
`"emmet.includeLanguages": {
        "twig": "html"
}`

Thèmes bootstrap: [https://bootswatch.com/](https://bootswatch.com/)

# Les controllers

Créer un controller:

`php bin/console create:controller`

Exemple de fonction d'un controller

```php  
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig', [
            'title' => 'bienvenue sur le site'
        ]);
    }
```

Les annotations /** **/ permettent de définir la route sur laquelle cette fonction va être appelée et 
`$this->render` permet d'afficher une vue.

Le premier paramètre est le template twig et le 2ème est un tableau associatif permettant de lier un nom de variable à une valeur. On pourra ensuite l'afficher dans notre template en utilisant l'interpolation comme ceci:

`<h1>{{ title }}</h1>`

#### Pour récupérer des données de la base de données, il faut d'abord se connecter à un repository, pour cela on utilise doctrine: 

`$repo = $this->getDoctrine()->getRepository(Article::class);`

Grace à l'injection de dépendances de Symfony, on peut récupérer directement le repository dont on a besoin comme ici:

```php
/**
     * @Route("/blog", name="blog")
     */
    // Symfony nous donne le repository ici
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }
```

## Attention à bien importer la classe Article avec un Use au début du fichier

#### puis on récupère les données que l'on veut, par exemple tous les articles:

`$articles = $repo->findAll();`

###### On peut aussi utiliser des fonctions telles que findOne, findBy, find, findOneBy(['title' => "Titre 1"]) ect... 

[https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository](https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository)

# Les routes

Si on a une fonction d'un controller ayant pour route `@Route("/", name="home")`

On peut appeler cette route dans un template en faisant `{{ path('blog_create') }}`

Une fonction de controller peut avoir plusieurs routes 

```php
    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/article/{id}/edit", name="blog_edit")
     * Cette fonction permet à la fois la création d'article et la modification d'un article en fonction de la route appelée
    */
```

# Les vues

Twig est le moteur de templates utilisé par Symfony.

Dans le dossier templates de Symfony, il y a le fichier base.html.twig qui sert de layout global, pouvant être utilisé sur chaque page. Pour cela, il faut que sur chaque autre fichier html.twig, il y ait en tout premier: `{% extends 'base.html.twig' %}` et qu'ensuite, le reste de la page soit englobé dans un block body (défini dans base.html.twig) comme ceci:

```php

{% extends 'base.html.twig' %}

{% block body}

    Contenu

{% endblock %}

```

#### Boucle for

```twig

{% for article in articles %}

    {{ article.title }}

{% endfor %}

```

##### Tous traitements sont faits dans des blocs {% %} et tous les affichages de variables se font avec des blocs {{ }}

# Gestion de la base de données - Doctrine

Doctrine est un ORM (Object Relational Mapping), il permet de faire le lien entre une application et une base de données. Dans le cas de Symfony, il permet aux classes php et aux objets de refléter les tables et les données de la base de données.

#### Entity -> Table 

#### Manager -> Manipuler les données 

#### Repository -> Selectionner les données

Une migration est un fichier permettant de définir la base de données et les tables. L'utilisation de fichiers est pratique pour avoir la même base de données entre développeurs, il suffit de faire marcher les scripts de migration.

Les fixtures sont des scripts permettant des jeux de données (fausses données). Dans laravel, cela est appelé "seeders".

Le nom de la BDD, le user et le password sont définis dans le .env

#### La base de données peut être générée en exécutant la commande: `php bin/console doctrine:database:create`

#### Pour créer une entity (table): `php bin/console make:entity`

#### Mettre à jour le script des migrations: `php bin/console make:migration`

#### Lancer les migrations: `php bin/console doctrine:migrations:migrate`

#### Pour pouvoir utiliser les fixtures (scripts générant des données), il faut faire: `composer require orm-fixtures --dev`

##### Pour créer une fixture: `php bin/console make:fixtures`

###### Pour vider la base de données: `php bin/console doctrine:schema:drop --force`

Exemple de fixture: 

```php

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $article = new Article();
            $article->setTitle("Titre de l'article ".$i)
                    ->setContent("Contenu de l'article ".$i)
                    ->setImage("http://placehold.it/350x150")
                    ->setCreatedAt(new \DateTime());
            $manager->persist($article);
        }

        $manager->flush();
    }
}

```

##### Pour charger les fixtures: `php bin/console doctrine:fixtures:load`
#### Attention, cela va purger la base de données et mettre les données des fixtures !

# Formulaires
    
Config
- packages
    - twig.yaml

Ajouter `form_themes: ['bootstrap_4_layout.html.twig']`

pour avoir accès à un formulaire bootstrapé directement, en n'utilisant que le code suivant:

```twig

{% extends 'base.html.twig' %}

{% form_theme formArticle 'bootstrap_4_layout.html.twig' %}

{% block body %}

    {{ form_start(formArticle) }}

        {{ form_row(formArticle.title, {'attr': {'placeholder': "Titre de l'article"}}) }}

        {{ form_row(formArticle.content, {'attr': {'placeholder': "Contenu de l'article"}}) }}

        {{ form_row(formArticle.image, {'attr': {'placeholder': "URL de l'image de l'article" }})}}

    {# Ou {{ form_widget(formArticle) }} #}

    <button type="submit" class="btn btn-success">Ajouter l'article</button>

    {{ form_end(formArticle) }}

{% endblock %}

```

Lors de la soumission d'un formulaire, il appelle la même fonction qui fait le rendu d'affichage du form.
Ainsi la fonction qui gère le formulaire ressemble à ça:

```php
/**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/article/{id}/edit", name="blog_edit")
     * Cette fonction permet à la fois la création d'article et la modification d'un article en fonction de la route appelée
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager){

        // Si on n'a pas d'article, cela veut dire qu'on va créer un article
        if(!$article){
            $article = new Article();
        }
        
        // Création du formulaire en lui donnant notre objet article, pour qu'il ait un modèle à suivre
        $form = $this->createFormBuilder($article)
                    ->add('title', TextType::class)
                    ->add('content', TextareaType::class)
                    ->add('image', TextType::class)
                    ->getForm();
        
        /* OU php bin/console make:form 
           ArticleType
           Article

           $form = $this->createForm(ArticleType::class, $article);

           Utile si on veut re utiliser le même formulaire
        */

        // Pour que le form prenne en compte la soumission du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et qu'il est valide
        if($form->isSubmitted() && $form->isValid()){
            // Si l'article n'a pas déjà d'id, c'est à dire qu'il est nouveau, on lui donne sa date de création
            if(!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }
            // On prépare le manager à entrer les données puis il le fait
            $manager->persist($article);
            $manager->flush();

            // Redirection sur la page de l'article créé/modifié
            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        // Affichage de la vue du formulaire
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            // editMode permet de savoir si on est en train d'éditer ou de créer un article
            'editMode' => $article->getId() !== null
        ]);
    }
```


### Vérification du formulaire

Liste des contraintes sur lesquels on peut vérifier la conformité des données 

https://symfony.com/doc/current/validation.html#supported-constraints

Pour les utiliser, il faut modifier directement l'entité:

ajouter `use Symfony\Component\Validator\Constraints as Assert;`

Exemple de contrainte sur la longueur d'une chaine de caractères:

```php

/**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min= 10, max=255, minMessage="Votre titre est trop court")
     */
    private $title;

```

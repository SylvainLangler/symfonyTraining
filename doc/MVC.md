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
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

# Vérification du formulaire

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
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

# Les controllers

Créer un controller:

`php bin/console create:controller`

Exemple de fonction d'un controller

```  
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

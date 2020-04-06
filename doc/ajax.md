# Ajax dans Symfony

[Tutoriel](https://www.youtube.com/watch?v=lygMPwUj9yU)

Exemple d'utilisation de l'ajax dans Symfony:

Si on est connecté, on peut voir tous les articles que l'on a déjà liké grâce à une icone différente.

```twig

    <a href="{{ path('article_like', {'id': article.id }) }}" class="btn btn-link js-like">
        {% if app.user and article.isLikedByUser(app.user) %}
            <i class="fas fa-thumbs-up"></i>
        {% else %}
            <i class="far fa-thumbs-up"></i>
        {% endif %}
        <span class="js-likes">{{ article.likes | length }}</span>
        <span class="js-label">J'aime</span>
    </a>

```

Au clic on appelle une route qui va mettre à jour nos likes (ajouter ou enlever un like selon le cas)

```php

    /**
     * Permet de liker ou unliker un article
     * 
     * @Route("/article/{id}/like", name="article_like")
     */
    public function like(Article $article, EntityManagerInterface $manager, PostLikeRepository $likeRepo) :
    Response{
        $user = $this->getUser();

        // Si aucun utilisateur n'est connecté
        if(!$user){
            return $this->json([
                'code' => 403,
                'message' => "Pas d'utilisateur connecté / Non autorisé"
            ], 403);
        }

        // Si l'article est déjà liké par l'utilisateur, on l'enlève
        if($article->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'article' => $article, 
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likeRepo->count(['article' => $article])
            ], 200);
        }

        // Si l'article n'est pas liké par l'utilisateur, on l'ajoute
        $like = new PostLike();
        $like->setArticle($article)
             ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likeRepo->count(['article' => $article])
        ], 200);
    }
```

Script JS qui va faire les appels sur cette route

```js

function onClickBtnLike(event){
    event.preventDefault();
    
    let url = this.href;
    let spanCount = this.querySelector('span.js-likes');
    let icone = this.querySelector('i');
    // Requete ajax
    axios.get(url).then(function(response){
        spanCount.textContent = response.data.likes;
        // Si fas -> j'aime déjà le contenu donc je change l'icone et inversement
        if(icone.classList.contains('fas')){
            icone.classList.replace('fas', 'far');
        }
        else{
            icone.classList.replace('far', 'fas');
        }
    }).catch(function(error) {
        // Si pas connecté
        if(error.response.status === 403){
            window.alert("Vous ne pouvez pas liker un article si vous n'êtes pas connecté")
        }
        else{
            window.alert("Une erreur s'est produite");
        }
    });
}

document.querySelectorAll('a.js-like').forEach(function(link){
    link.addEventListener('click', onClickBtnLike);
})

```
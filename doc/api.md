# Créer une api avec Symfony

### Api à la main

Créer des routes qui retournent des réponses JSON

Exemple: route get tous les articles

```php

    /**
     * @Route("/api/articles", name="api_articles_index", methods={"get"})
     */
    public function index(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {
        $articles = $articleRepository->findAll();

        // Normalisation des données: transformer des objets en tableaux associatifs simples et ransformation en texte

        // article:read = étiquette sur les données que l'on veut extraire au moment de la normalisation des données, attention il faut éviter de tourner en boucle (entre article et comment par ex)
        return $this->json($articles, 200, [], ['groups' => 'article:read']);

    }
```

### Utiliser ApiPlatform

Installation de tous les packages utiles:

`composer require api`

Optionnel: 

config
- packages
  - routes
    - api_platform.yaml

`prefix: /apip` au lieu de `prefix: /api`

Sur le fichier d'une entité: ajouter @ApiResource comme ceci (ne pas oublier le namespace)

```php

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ApiResource
 */
class Article

```

Puis vérifier sur `/apip`, par exemple: `http://localhost:8000/apip` et tester les routes avec postman ou équivalent, les routes sont fonctionnelles

#### Exemple de besoin particulier

Créer son propre DataPersister dans le cas ou il faut ajouter un attribut createdAt lors d'un post:

Créer un dossier DataPersister dans Src

Créer le fichier EntitéPersister, par exemple ici: ArticlePersister.php

```php

<?php

namespace App\DataPersister;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;

class ArticlePersister implements DataPersisterInterface{

    public function __construct(EntityManagerInterface $manager){
        $this->em = $manager;
    }

    public function supports($data): bool
    {
        return $data instanceof Article;
    }
    
    public function persist($data)
    {
        // 1. Mettre une date de création sur l'article
        $data->setCreatedAt(new \DateTime());

        // 2. Demander à Doctrine de persister
        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data)
    {
        // 1. Demander à doctrine de supprimer l'article
        $this->em->remove($data);
        $this->em->flush();
    }
}

```
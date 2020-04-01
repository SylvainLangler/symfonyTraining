Les fixtures sont des scripts permettant des jeux de données (fausses données). Dans laravel, cela est appelé "seeders".
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

# Librairie Faker

### Faker permet de générer des données aléatoires

### Pour pouvoir s'en servir dans le projet il suffit de faire:

`composer require fzaninotto/faker --dev`

#### Exemple d'utilisation de faker dans une fixture:

```php

$faker = \Faker\Factory::create('fr_FR');

// Créer 3 catégories
for($i = 1; $i <= 3; $i++){
    $category = new Category();
    $category->setTitle($faker->sentence())
             ->setDescription($faker->paragraph());

    $manager->persist($category);
}

$manager->flush();

```
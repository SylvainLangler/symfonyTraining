<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 catégories
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);

            // Créer entre 4 et 6 articles

            for($j = 1; $j <= mt_rand(4,6); $j++){
                $article = new Article();

                $article->setTitle($faker->sentence())
                        ->setContent(join($faker->paragraphs(5)))
                        ->setImage('http://placehold.it/350x150')
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                // Créer entre 4 et 10 commentaires

                for($k = 1; $k <= mt_rand(4,10); $k++){
                    $comment = new Comment();

                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = '-'.$interval->days.' days';
                    $comment->setAuthor($faker->name)
                            ->setContent(join($faker->paragraphs(2)))
                            ->setCreatedAt($faker->dateTimeBetween($days))
                            ->setArticle($article);

                    $manager->persist($comment);
                }
            }
        }

        

        $manager->flush();
    }
}

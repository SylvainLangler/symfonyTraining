<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\Profile;
use App\Entity\Category;
use App\Entity\PostLike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        
        $faker = \Faker\Factory::create('fr_FR');

        $users = [];

        $user = new User();
        $profile = new Profile();
        $profile->setTitle('Profil 1')
                ->setDescription('Description du profil 1');

        $user->setFirstName('Sylvain')
             ->setLastName('Langler')
             ->setEmail('langlersylvain@gmail.com')
             ->setRoles(['ROLE_ADMIN'])
             ->setProfile($profile)
             ->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'Sylvain1234'
        ));

        $users[] = $user;

        $manager->persist($user);

        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setEmail($faker->email)
                 ->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    'Sylvain1234'
                ));
            $manager->persist($user);
            $users[] = $user;
        }


        // Créer 3 catégories
        for($i = 1; $i <= 3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());

            $manager->persist($category);


            $product = new Product();
            $product->setName('Produit 1')
                    ->setPrice("20€");

            // Créer entre 4 et 6 articles
            
            for($j = 1; $j <= mt_rand(4,6); $j++){
                $article = new Article();

                $article->setTitle($faker->sentence())
                        ->setContent(join($faker->paragraphs(5)))
                        ->setImage('http://placehold.it/350x150')
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article);

                $product->addArticle($article);

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

                // créer des likes
                for($a = 0; $a < mt_rand(0,10); $a++){
                    $like = new PostLike();
                    $like->setArticle($article)
                        ->setUser($faker->randomElement($users));
                    $manager->persist($like);
                }
            }

            $manager->persist($product);
        }

        

        $manager->flush();
    }
}

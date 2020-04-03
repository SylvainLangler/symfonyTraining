<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('blog/articles.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(TranslatorInterface $translator){
        $test = $translator->trans("C'est bien Symfony");
        /* OU dans les vues : {{ test | trans }} */
        return $this->render('blog/home.html.twig', [
            'title' => 'bienvenue sur le site',
            'test' => $test
        ]);
    }

    /**
     * @Route("/blog/article/{id}", name="blog_show")
     */
    public function show(Article $article){
       // $repo = $this->getDoctrine()->getRepository(Article::class);
       // $article = $repo->find($id);
        return $this->render('blog/article.html.twig', [
            'article' => $article
        ]);
    }

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
                    ->add('category', EntityType::class, [
                        'class' => Category::class,
                        'choice_label' => 'title'
                    ])
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

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile(){
         return $this->render('user/profile.html.twig');
    }

    /**
     * @Route("/requetes", name="requetes")
     */
    public function requetes(ArticleRepository $articleRepository, ProductRepository $productRepository, CommentRepository $commentRepository, EntityManagerInterface $manager){

        // SELECT
        $article = $articleRepository->findOneById(5);

        // INSERT

        $category = new Category();
        $category->setTitle('Catégorie test '.mt_rand(1, 1000))
                 ->setDescription('Description de la catégorie test');

        $manager->persist($category);
        $manager->flush();

        // UPDATE

        // Exemple de findOneBy, on peut aussi utiliser findBy pour récupérer un tableau
        $product = $productRepository->findOneBy([
            'id' => 1
            // On peut mettre d'autres "where" ici
        ]);

        $product->setPrice('40€');
        $manager->persist($product);
        $manager->flush();

        // DELETE

        $article_delete = $articleRepository->findOneById(3);

        if($article_delete){
            // Lorsque l'on supprime cet article, tous les commentaires liés à cet article sont supprimés également: effet cascade
            $manager->remove($article_delete);
            $manager->flush();
        }
        
        return $this->render('exemples/requetes.html.twig', [
            'article' => $article,
            'category' => $category,
            'product' => $product
        ]);
    }

}

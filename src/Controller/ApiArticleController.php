<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiArticleController extends AbstractController
{
    /**
     * @Route("/api/articles", name="api_articles_index", methods={"get"})
     */
    public function index(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {
        $articles = $articleRepository->findAll();

        // Normalisation des données: transformer des objets en tableaux associatifs simples et transformation en texte (=serialization)

        // Sans serializer mais avec normalizer:
        // $articlesNormalises = $normalizer->normalize($articles, null, ['groups' => 'article:read']);
        // $json = json_encode($articlesNormalises);

        // Avec:
        // $json = $serializer->serialize($articles, 'json', ['groups' => 'article:read']);
        // $response = new JsonResponse($json, 200, [], true);

        // Sans les deux:

        // article:read = étiquette sur les données que l'on veut extraire au moment de la normalisation des données, attention il faut éviter de tourner en boucle (entre article et comment par ex)
        return $this->json($articles, 200, [], ['groups' => 'article:read']);

    }

    /**
     * @Route("/api/articles", name="api_articles_store", methods={"post"})
     */
    public function store(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, CategoryRepository $categoryRepo, ValidatorInterface $validator){
        $json = $request->getContent();

        // Dé-sérialisation: part d'un tableau pour créer une entité/objet

        try{
            $article = $serializer->deserialize($json, Article::class, 'json');
            $article->setCreatedAt(new \DateTime());

            $category = $categoryRepo->findOneById(1);
            $article->setCategory($category);

            // S'il y a des erreurs par rapport aux données envoyées, par exemple si une donnée est manquante
            $errors = $validator->validate($article);

            if(count($errors) > 0){
                return $this->json($errors, 400);
            }

            $manager->persist($article);
            $manager->flush();
        } catch(NotEncodableValueException $e){
            return $this->json([
                "status" => 400,
                "message" => $e->getMessage()
            ], 400);
        }

        return $this->json($article, 201, [], ['groups' => 'article:read']);
    }
}

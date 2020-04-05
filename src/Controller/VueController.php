<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VueController extends AbstractController
{
    /**
     * @Route("/vue", name="vue")
     */
    public function index()
    {
        $words = ['sky', 'cloud', 'wood', 'rock', 'forest','mountain', 'breeze'];

        return $this->render('vue/index.html.twig', [
            'words' => $words
        ]);
    }
}

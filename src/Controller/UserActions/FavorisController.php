<?php

namespace App\Controller\UserActions;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    public function index(ArticleRepository $article): Response
    {
        $articles = $article->findAll();
        return $this->render('favoris/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('Afavoris/{id}', name: 'favoris_add')]
    public function addFavoris($id, Request $request, EntityManagerInterface $entityManager) :Response
    {
        $item = $request->request->get('add');
        $user = $this->getUser();
        $user->setFavoris($item);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    #[Route('Rfavoris/{id}', name: 'favoris_remove')]
    public function removeFavoris($id, EntityManagerInterface $entityManager) :Response
    {
        $article = $this->getDoctrine()->getRepository('App:Article')->find($id);
        $user = $this->getUser();
        foreach ($user->getFavoris() as $key => $fav){
            if($fav == $article->getId()){
                $user->removeFavoris($key);
            }
        }
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}

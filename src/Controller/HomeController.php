<?php

namespace App\Controller;

use App\Entity\User;
use App\Mercure\CookieGenerator;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository, CookieGenerator $cookieGenerator): Response
    {
      
        $response = $this->render('home/index.html.twig', [
            'articles' => $articleRepository->findBy([],[
                'CreatedAt' => 'desc'
            ]),
            'users' => $userRepository->findAll()
        ]);
            if ($this->getUser()) {
                $response->headers->setCookie($cookieGenerator->generate($this->getUser()));        
            }
        return $response;
    }

//     #[Route('/ping/1', name: 'ping')]
//     public function ping(MessageBusInterface $bus, ?User $user = null, SerializerInterface $serializerInterface):Response
//     {
//         $target = '';
//         if($user !== null) {
//             $target = "/chat/user/{$user->getId()}";
//         }
//         $update = new Update(['/chat'],"[]");
// // , $target $serializerInterface->serialize($this->getUser(), 'json', ['groups' => 'public']),true ,$target
//         $bus->dispatch($update);

//         return $this->redirectToRoute('home');
//     }       
    public function home(): Response
    { 
        return $this->render('message/index.html.twig');
    }
}
<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowersController extends AbstractController
{
    public function index(UserRepository $users): Response
    {
        $users = $users->findAll();
        return $this->render('followers_list/index.html.twig', [
            'Users' => $users
        ]);
    }
    
    #[Route('/add', name: 'add_follower', methods:["POST"])]
    public function addFriend(Request $request, EntityManagerInterface $entityManager, UserRepository $otherUser, NotificationsController $notifie){
        $friend = $request->request->get('add');
        $user = $this->getUser();
        $user->setFriends($friend);
        $follower = $otherUser->find($friend);
        $follower->setFollowers((string)$user->getId());

        $target = $user->getName()." ".$user->getPrename();
        $NotificationContent = $target." Started Following You !!";
        $notifie->sendNotification($follower, $NotificationContent, $target);
        $entityManager->flush();
        return $this->redirectToRoute('home');
    }
}
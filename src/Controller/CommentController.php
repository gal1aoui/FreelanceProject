<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route('accept/{id}', name: 'comment_accept')]
    public function CommentAccepted($id, EntityManagerInterface $entityManager, NotificationsController $notifie)
    {
        $comment = $this->getDoctrine()->getRepository('App:Comment')->find($id);
        $comment->setStatus(1);
        $entityManager->flush();
        $target = $this->getUser()->getName()." ".$this->getUser()->getPrename();
        $NotificationContent = " Has Accept Your Offer for ". $comment->getOffer()."DT on Mission ". $comment->getArticle()->getTitle();  
        $notifie->sendNotification($comment->getUser(), $NotificationContent, $target);

        return $this->json([
            'CommentId' => $id,
            'CommentStatus' => $comment->getStatus()
        ], 200);

    }
    #[Route('refuse/{id}', name: 'comment_refuse')]
    public function CommentRefused($id, EntityManagerInterface $entityManager, NotificationsController $notifie)
    {
        $comment = $this->getDoctrine()->getRepository('App:Comment')->find($id);
        $comment->setStatus(0);
        $entityManager->flush();
        $target = $this->getUser()->getName()." ".$this->getUser()->getPrename();
        $NotificationContent = " Has Refuse Your Offer for ". $comment->getOffer()."DT on Mission ". $comment->getArticle()->getTitle();  
        $notifie->sendNotification($comment->getUser(), $NotificationContent, $target);

        return $this->json([
            'CommentId' => $id,
            'CommentStatus' => $comment->getStatus()
        ], 200);

    }
}

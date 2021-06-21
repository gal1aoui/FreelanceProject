<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class NotificationsController extends AbstractController
{
    private $em;
    private $publisher;
    private $serializer;

    public function __construct(EntityManagerInterface $entityManagerInterface, PublisherInterface $pub, SerializerInterface $serializer)
    {
        $this->em = $entityManagerInterface;
        $this->publisher = $pub;
        $this->serializer = $serializer;
    }

    #[Route('/notifications', name: 'notifications')]
    public function index(NotificationRepository $not): Response
    {
        return $this->render('notifications/index.html.twig', [
            'notifications' => $not->findBy(['user' => $this->getUser()],['createdAt' => 'desc']),
        ]);
    }

    public function sendNotification(User $user, string $subject, string $target)
    {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSubject($subject);
            $notification->setCreatedAt(new \DateTime());
            $notification->setTarget($target);

            $this->em->persist($notification);
            $this->em->flush();

            $messageSerialized = $this->serializer->serialize($notification, 'json', [
                'attributes' => ['id', 'subject', 'target','createdAt', 'user' => ['id']]
            ]);
            $update = new Update(["/notification/{$user->getEmail()}"],$messageSerialized );
            $this->publisher->__invoke($update);
    }
}

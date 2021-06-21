<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MessageController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine', 'picture'];

    private $em;
    private $mr;
    private $pr;
    private $p;

    public function __construct(EntityManagerInterface $entityManagerInterface, MessageRepository $messageRepository, ParticipantRepository $participantRepository, PublisherInterface $publisher)
    {
        $this->em = $entityManagerInterface;
        $this->mr = $messageRepository;
        $this->pr = $participantRepository;
        $this->p = $publisher;
    }

    #[Route('/message/{id}', name: 'ownMessages', methods:['GET'])]
    public function index( Conversation $conversation): Response
    {
        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $this->mr->findMessageByConversationId($conversation->getId());

        array_map(function ($message){
            $message->setMine($message->getUser()->getId() === $this->getUser()->getId() ? true : false);
            $message->setPicture($message->getUser()->getPicture());
        }, $messages);

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    #[Route('/message/{id}', name: 'setMessage', methods:['POST'])]
    public function newMessage(Request $request, Conversation $conversation, SerializerInterface $serializer){
        
        $user = $this->getUser();
        $recipient = $this->pr->findParticipantByCIdAndUId($conversation->getId(), $user->getId());        

        $content = $request->request->get('content', null);

        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);
        $message->setCreatedAt(new \DateTime());
        $message->setPicture($user->getPicture());
        
        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->em->getConnection()->beginTransaction();
        try{
            $this->em->persist($message);
            $this->em->persist($conversation);
            $this->em->flush();

            $this->em->commit();
        }catch(\Exception $e){
            $this->em->rollback();
            throw $e;
        }
        $message->setMine(false);

        $messageSerialized = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'mine', 'picture', 'conversation' => ['id']]
        ]);

        $update = new Update([
                "/conversation/{$conversation->getId()}",
                "/conversation/{$recipient->getUser()->getEmail()}"
            ],$messageSerialized,
        );
        $this->p->__invoke($update);

        $message->setMine(true);
        
        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}

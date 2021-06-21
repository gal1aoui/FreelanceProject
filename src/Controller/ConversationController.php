<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Mercure\CookieGenerator;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\WebLink\Link;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{
    private $ur;
    private $em;
    private $con;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository)
    {
        $this->ur = $userRepository;
        $this->em = $entityManager;
        $this->con = $conversationRepository;
    }

    #[Route('/conversation', name: 'setConversation', methods:["POST"])]
    public function setConvs(Request $request): Response
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $this->ur->find($otherUser);

        if(is_null($otherUser)){
            throw new \Exception("The User was not found");
        }

        if($otherUser->getId() === $this->getUser()->getId()){
            throw new \Exception("Are you Crazy trying to talk to ur Self ?");
        }

        $conversation = $this->con->findConversationByParticipants($this->getUser()->getId(), $otherUser->getId());

        if(count($conversation)){
            throw new \Exception("The Conversation already exists");
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $this->em->getConnection()->beginTransaction();

        try{
            $this->em->persist($conversation);
            $this->em->persist($participant);
            $this->em->persist($otherParticipant);

            $this->em->flush();
            $this->em->commit();
        }catch(\Exception $e){
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);

        // return $this->render('conversation/index.html.twig', [
        //     'controller_name' => 'ConversationController',
        // ]);
    }

    #[Route('/conversation', name: 'getConversation', methods:["GET"])]
    public function getConvs(Request $request){
        $conversation = $this->con->findConversationByUser($this->getUser()->getId());
        
        $hubUrl = $this->getParameter('mercure.default_hub');

        $this->addLink($request, new Link('mercure',$hubUrl));  

        return  $this->json($conversation);
    }
    
}

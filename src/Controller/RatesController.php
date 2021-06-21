<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatesController extends AbstractController
{
    #[Route('/rating/{id}', name: 'rates', methods:['POST']) ]
    public function new($id, Request $request, EntityManagerInterface $em, RatingRepository $rat, NotificationsController $notifie): Response
    {
        $article = $this->getDoctrine()->getRepository('App:Article')->find($id);
        $user = $rat->findOneBy(['User' => $this->getUser(), 'Article' => $id]);

        $data = $request->getContent();
        $json_data = json_decode($data, true);
        $value = $json_data['rate'];

        if( $user ){      
            $inte = (int)$value;
            $rat->findOneBy(['User' => $this->getUser(), 'Article' => $id ])->setStars($inte);

            $target = $this->getUser()->getName()." ".$this->getUser()->getPrename();
            $NotificationContent =  " Has Update his Rate into Your Mission ". $article->getTitle();
            $notifie->sendNotification($article->getUser(), $NotificationContent, $target);
            $em->flush();   
        }else{
            $rate = new Rating();
            $int = (int)$value;
            $rate->setArticle($article);
            $rate->setUser($this->getUser());
            $rate->setStars($int);
            $target = $this->getUser()->getName()." ".$this->getUser()->getPrename();
            $NotificationContent = " Has Rate into Your Mission ".$article->getTitle();
            $notifie->sendNotification($article->getUser(), $NotificationContent, $target);
            $em->persist($rate);
            $em->flush();
        }
       $qp = $rat->findBySum($article);
        return $this->json([
            'Stars' => $rat->count([ 'Article' => $article]),
            'Total' => $qp
        ]);
    }

}

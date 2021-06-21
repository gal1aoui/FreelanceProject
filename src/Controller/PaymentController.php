<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{    

    #[Route('/payment', name: 'paymentHome')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig');
    }
    #[Route('/payment/succes', name: 'Spayment')]
    public function SuccesIndex(): Response
    {
        return $this->render('payment/succes.html.twig', [
            
        ]);
    }
    #[Route('/payment/error', name: 'Epayment')]
    public function ErrorIndex(): Response
    {
        return $this->render('payment/error.html.twig', [
            
        ]);
    }

    #[Route('/create-checkout-session/{id}/{comment}', name: 'payment')]
    public function check($id,$comment, ArticleRepository $article, CommentRepository $commentRepository): Response
    {
       $user = $article->find($id);
       $amount = $commentRepository->find($comment);
        \Stripe\Stripe::setApiKey('sk_test_51IyD2fKlRL2YJsUlN9MtQHHzSEZaeerOhIM6DdidJbPR7wfuI1q8bguDIJ1lEyY8qZafpQS4UlfAUFnRbd1JeKQi00B270wYW3');
            $session = \Stripe\Checkout\Session::create([
              'payment_method_types' => ['card'],
              'line_items' => [[
                'price_data' => [
                  'currency' => 'usd',
                  'product_data' => [
                    'name' => "Pay ".$user->getUser()->getName()." ".$user->getUser()->getPrename()."\n For the Mission : ".$user->getTitle()
                  ],
                  'unit_amount' => (int)$amount->getOffer() * 100
                ],
                'quantity' => 1,
              ]],
              'mode' => 'payment',
              'success_url' => $this->generateUrl('Spayment', [], UrlGeneratorInterface::ABSOLUTE_URL),
              'cancel_url' => $this->generateUrl('Epayment', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
            
        return new JsonResponse(['id' => $session->id]);        
    }
    
}
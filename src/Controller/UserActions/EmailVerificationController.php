<?php

namespace App\Controller\UserActions;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailVerificationController extends AbstractController{

    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/Account-Confirmation', name: 'user_confirm', methods: ['GET', 'POST'])]
    function VerifyEmail():Response
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $this->getUser(),
                (new TemplatedEmail())
                    ->from(new Address('freelancetun6@gmail.com', 'FreeLanceI Mail_Verification'))
                    ->to($this->getUser()->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
        );
        return $this->render('user/email.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
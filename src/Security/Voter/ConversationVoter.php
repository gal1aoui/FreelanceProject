<?php

namespace App\Security\Voter;

use App\Entity\Conversation;
use App\Repository\ConversationRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter
{
    private $cr;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->cr = $conversationRepository;
    }

    const VIEW = 'view';
    protected function supports(string $attribute, $subject)
    {
        return $attribute == self::VIEW && $subject instanceof Conversation;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $result = $this->cr->checkIfUserisParticipant($subject->getId(), $token->getUser()->getId());

        return !!$result;
    }
}
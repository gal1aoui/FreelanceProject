<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Conversation::class, mappedBy="lastMessage", cascade={"persist", "remove"})
     */
    private $lastMessageId;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $conversation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    private $mine;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    public function getMine()
    {
        return $this->mine;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($mine)
    {
        $this->picture = $mine;
        return $this;
    }

    public function setMine($mine)
    {
        $this->mine = $mine;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastMessageId(): ?Conversation
    {
        return $this->lastMessageId;
    }

    public function setLastMessageId(?Conversation $lastMessageId): self
    {
        // unset the owning side of the relation if necessary
        if ($lastMessageId === null && $this->lastMessageId !== null) {
            $this->lastMessageId->setLastMessage(null);
        }

        // set the owning side of the relation if necessary
        if ($lastMessageId !== null && $lastMessageId->getLastMessage() !== $this) {
            $lastMessageId->setLastMessage($this);
        }

        $this->lastMessageId = $lastMessageId;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

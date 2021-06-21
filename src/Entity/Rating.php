<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ratings")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="ratings")
     */
    private $Article;

    /**
     * @ORM\Column(type="integer")
     */
    private $Stars;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->User;
    }

    public function setUser(?user $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->Article;
    }

    public function setArticle(?Article $Article): self
    {
        $this->Article = $Article;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->Stars;
    }

    public function setStars(int $Stars): self
    {
        $this->Stars = $Stars;

        return $this;
    }
}

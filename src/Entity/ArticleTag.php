<?php

namespace App\Entity;

use App\Repository\ArticleTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleTagRepository::class)]
class ArticleTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articleTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'articleTags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $Tag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->Tag;
    }

    public function setTag(?Tag $Tag): static
    {
        $this->Tag = $Tag;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use App\Utils\TagsHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
	#[Assert\NotBlank(
		message: 'Title of article is empty',
	)]
    private ?string $title = null;

	#[ORM\Column(length: 64)]
	private ?string $hash = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: "Tag", fetch: 'EAGER')]
	#[ORM\JoinTable(name: "article_tag")]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, ArticleTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

	public function setTags(array $tags): static
	{
		$this->tags->clear();
		foreach ($tags as $tag) {
			$this->tags->add($tag);
		}

		return $this;
	}

	public function removeTag(Tag $tag): self
	{
		if ($this->tags->contains($tag)) {
			$this->tags->removeElement($tag);
		}

		return $this;
	}

	public function getHash(): ?string
	{
		return $this->hash;
	}

	#[ORM\PrePersist]
	public function generateHash(): void
	{
		$tagNames = $this
			->getTags()
			->map(fn (Tag $tag) => $tag->getName())
			->toArray();

		$this->hash = (new TagsHasher($tagNames))->getHash();
	}
}

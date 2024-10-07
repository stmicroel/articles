<?php

namespace App\Mapper;

use App\Entity\Tag;
use App\Entity\Article;
use JsonSerializable;

/**
 * Mapping of tag
 */
readonly class ArticleMapper implements JsonSerializable {
	/**
	 * Constructor
	 *
	 * @param Article $article Article entity
	 */
	public function __construct(private Article $article)
	{
	}

	/**
	 * Serialize to JSON
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return [
			'id' => $this->article->getId(),
			'title' => $this->article->getTitle(),
			'tags' => array_map(
				fn(Tag $tag) => (new TagMapper($tag))->jsonSerialize(),
				$this
					->article
					->getTags()
					->toArray()
			),
		];
	}
}
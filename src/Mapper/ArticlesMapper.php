<?php

namespace App\Mapper;

use App\Entity\Tag;
use App\Entity\Article;
use JsonSerializable;

/**
 * Mapping of tag
 */
readonly class ArticlesMapper implements JsonSerializable {
	/**
	 * Constructor
	 *
	 * @param Article[] $articles Articles entities
	 */
	public function __construct(private array $articles)
	{
	}

	/**
	 * Serialize to JSON
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return array_map(fn(Article $article) => (new ArticleMapper($article))->jsonSerialize(), $this->articles);
	}
}
<?php

namespace App\Mapper;

use App\Entity\Tag;
use JsonSerializable;

/**
 * Mapping of tag
 */
readonly class TagMapper implements JsonSerializable {
	/**
	 * Constructor
	 *
	 * @param Tag $tag Tag entity
	 */
	public function __construct(private Tag $tag)
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
			'id' => $this->tag->getId(),
			'name' => $this->tag->getName(),
		];
	}
}
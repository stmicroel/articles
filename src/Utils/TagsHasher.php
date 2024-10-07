<?php

namespace App\Utils;

/**
 * Hash from array of tags
 */
class TagsHasher {
	/**
	 * Constructor
	 *
	 * @param array $tags Array of tags
	 */
	public function __construct(private array $tags)
	{
	}

	/**
	 * Get hash
	 *
	 * @return string|null
	 */
	public function getHash(): ?string
	{
		if (!$this->tags) {
			return null;
		}

		sort($this->tags);
		return hash('sha256', implode('', $this->tags));
	}
}
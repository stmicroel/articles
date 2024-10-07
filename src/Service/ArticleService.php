<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Exception\ValidationException;
use App\Repository\TagRepository;
use App\Utils\TagsHasher;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * Article service
 */
class ArticleService {
	/**
	 * Constructor
	 *
	 * @param ArticleRepository $articleRepository Repository for article entity
	 * @param TagRepository $tagRepository Repository for tag entity
	 * @param EntityManagerInterface $entityManager Entity manager
	 */
	public function __construct(
		private readonly ArticleRepository $articleRepository,
		private readonly TagRepository $tagRepository,
		private readonly EntityManagerInterface $entityManager,
	)
	{
	}

	/**
	 * Create new article
	 *
	 * @param string $title Title of article
	 * @param array $tagIds Tag identifiers
	 * @return Article
	 * @throws ValidationFailedException
	 */
	public function create(string $title, array $tagIds): Article
	{
		$tags = $this->getTagsByIds($tagIds);

		$article = new Article();
		$article->setTitle($title);
		$article->setTags($tags);

		try {
			$this->entityManager->persist($article);
			$this->entityManager->flush();
		} catch (UniqueConstraintViolationException) {
			throw new ValidationException('Article already exists');
		}

		return $article;
	}

	/**
	 * Delete article
	 *
	 * @param int $articleId Article identifier
	 * @return bool
	 */
	public function delete(int $articleId): bool
	{
		$article = $this->articleRepository->find($articleId);
		if (!$article) {
			throw new ValidationException('Article not found');
		}

		foreach ($article->getTags() as $tag) {
			$article->removeTag($tag);
		}
		$this->entityManager->flush();

		$this->entityManager->remove($article);
		$this->entityManager->flush();

		return true;
	}

	/**
	 * Get article
	 *
	 * @param int $articleId Article identifier
	 * @return Article
	 */
	public function get(int $articleId): Article
	{
		$article = $this->articleRepository->find($articleId);
		if (!$article) {
			throw new ValidationException('Article not found');
		}

		return $article;
	}

	/**
	 * Get article
	 *
	 * @param array $tagNames Array of tag names
	 * @return Article[]
	 */
	public function search(array $tagNames): array
	{
		$hash = (new TagsHasher($tagNames))->getHash();
		return $this->articleRepository->findByHash($hash);
	}

	/**
	 * Edit article
	 *
	 * @param int $articleId Article identifier
	 * @param string $title Title of article
	 * @param array $tagIds Tag identifiers
	 * @return Article
	 * @throws ValidationFailedException
	 */
	public function edit(int $articleId, string $title, array $tagIds): Article
	{
		$article = $this->articleRepository->find($articleId);
		if (!$article) {
			throw new ValidationException('Article not found');
		}

		$tags = $this->getTagsByIds($tagIds);

		$article->setTitle($title);
		$article->setTags($tags);
		$article->generateHash();

		try {
			$this->entityManager->persist($article);
			$this->entityManager->flush();
		} catch (UniqueConstraintViolationException) {
			throw new ValidationException('Article already exists');
		}

		return $article;
	}

	/**
	 * Get tags by ids with validation
	 *
	 * @param array $tagIds Tag identifiers
	 * @return array
	 */
	private function getTagsByIds(array $tagIds): array
	{
		if (!$tagIds) {
			return [];
		}

		$tags = $this->tagRepository->findByIds($tagIds);
		if (count($tagIds) !== count($tags)) {
			throw new ValidationException('Invalid tags provided');
		}

		return $tags;
	}
}
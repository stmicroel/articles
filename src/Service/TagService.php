<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Tag service
 */
class TagService {
	/**
	 * Constructor
	 *
	 * @param ValidatorInterface $validator Validator
	 * @param TagRepository $tagRepository Repository for tag entity
	 * @param EntityManagerInterface $entityManager Entity manager
	 */
	public function __construct(
		private readonly ValidatorInterface $validator,
		private readonly TagRepository $tagRepository,
		private readonly EntityManagerInterface $entityManager,
	)
	{
	}

	/**
	 * Create new tag
	 *
	 * @param string $name Name of tag
	 * @return Tag
	 * @throws ValidationFailedException
	 */
	public function create(string $name): Tag
	{
		$tag = new Tag();
		$tag->setName($name);

		$errors = $this->validator->validate($tag);
		if ($errors->count()) {
			throw new ValidationException($errors->get(0)->getMessage());
		}

		try {
			$this->entityManager->persist($tag);
			$this->entityManager->flush();
		} catch (UniqueConstraintViolationException) {
			throw new ValidationException('Tag already exists');
		}

		return $tag;
	}

	/**
	 * Edit tag
	 *
	 * @param int $id Id
	 * @param string $name Name
	 * @return Tag
	 * @throws ValidationFailedException
	 */
	public function edit(int $id, string $name): Tag
	{
		$tag = $this->tagRepository->findById($id);
		if (!$tag) {
			throw new ValidationException('Tag not found');
		}

		$tag->setName($name);

		$errors = $this->validator->validate($tag);
		if ($errors->count()) {
			throw new ValidationException($errors->get(0)->getMessage());
		}

		try {
			$this->entityManager->persist($tag);
			$this->entityManager->flush();
		} catch (UniqueConstraintViolationException) {
			throw new ValidationException('Tag already exists');
		}

		return $tag;
	}
}
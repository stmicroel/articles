<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

	/**
	 * Retrieve tag by id
	 *
	 * @param int $id Entity id
	 * @return Tag|null
	 */
	public function findById(int $id): ?Tag
	{
		return $this->find($id);
	}

	/**
	 * Retrieve tags by ids
	 *
	 * @param array $ids Tag ids
	 * @return Article[]
	 */
	public function findByIds(array $ids): array
	{
		return $this->findBy(['id' => $ids]);
	}
}

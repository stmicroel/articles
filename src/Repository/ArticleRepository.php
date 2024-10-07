<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

	public function findByHash(?string $tagsHash)
	{
		$query = $this->createQueryBuilder('a');
		if ($tagsHash) {
			$query
				->where('a.hash=:tagsHash')
				->setParameter('tagsHash', $tagsHash);
		}

		return $query
			->getQuery()
			->getResult();
	}
}

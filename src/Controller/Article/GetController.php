<?php

namespace App\Controller\Article;

use App\Exception\ValidationException;
use App\Mapper\ArticleMapper;
use App\Service\ArticleService;
use App\Controller\AbstractJsonController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Get article
 */
class GetController extends AbstractJsonController
{
	/**
	 * Constructor
	 *
	 * @param ArticleService $articleService Article service
	 */
	public function __construct(private readonly ArticleService $articleService)
	{
	}

	#[Route('/article/{id}', name: 'app_article_get', methods: ['GET'])]
	public function __invoke(Request $request, int $id): JsonResponse
	{
		try {
			$article = $this->articleService->get($id);
		} catch (ValidationException $e) {
			return $this->fail($e);
		}

		return $this->success(new ArticleMapper($article));
	}
}

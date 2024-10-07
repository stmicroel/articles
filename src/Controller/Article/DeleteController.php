<?php

namespace App\Controller\Article;

use App\Exception\ValidationException;
use App\Service\ArticleService;
use App\Controller\AbstractJsonController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Delete article
 */
class DeleteController extends AbstractJsonController
{
	/**
	 * Constructor
	 *
	 * @param ArticleService $articleService Article service
	 */
	public function __construct(private readonly ArticleService $articleService)
	{
	}

	#[Route('/article/{id}', name: 'app_article_delete', methods: ['DELETE'])]
	public function __invoke(Request $request, int $id): JsonResponse
	{
		try {
			$this->articleService->delete($id);
		} catch (ValidationException $e) {
			return $this->fail($e);
		}

		return $this->success();
	}
}

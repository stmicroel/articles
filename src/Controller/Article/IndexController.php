<?php

namespace App\Controller\Article;

use App\Exception\ValidationException;
use App\Mapper\ArticlesMapper;
use App\Service\ArticleService;
use App\Controller\AbstractJsonController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Search articles
 */
class IndexController extends AbstractJsonController
{
	/**
	 * Param "Tag names"
	 */
	private const PARAM_TAG_NAME = "tag_name";

	/**
	 * Constructor
	 *
	 * @param ArticleService $articleService Article service
	 */
	public function __construct(private readonly ArticleService $articleService)
	{
	}

	#[Route('/article', name: 'app_article_get', methods: ['GET'])]
	public function __invoke(Request $request): JsonResponse
	{
		$tagNames = (array)$request->get(self::PARAM_TAG_NAME);
		try {
			$articles = $this->articleService->search($tagNames);
		} catch (ValidationException $e) {
			return $this->fail($e);
		}

		return $this->success(new ArticlesMapper($articles));
	}
}

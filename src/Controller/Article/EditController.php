<?php

namespace App\Controller\Article;

use App\Mapper\ArticleMapper;
use App\Service\ArticleService;
use App\Exception\ValidationException;
use App\Controller\AbstractJsonController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Edit article
 */
class EditController extends AbstractJsonController
{
	/**
	 * Param "name of article"
	 */
	private const string PARAM_TITLE = 'title';

	/**
	 * Param "tag identifiers"
	 */
	private const string PARAM_TAG_IDS = 'tag_ids';

	/**
	 * Constructor
	 *
	 * @param ArticleService $articleService Article service
	 */
	public function __construct(private readonly ArticleService $articleService)
	{
	}

	#[Route('/article/{id}/edit', name: 'app_article_edit', methods: ['PATCH'])]
	public function __invoke(Request $request, int $id): JsonResponse
	{
		$title = (string)$request->get(self::PARAM_TITLE);
		$tagIds = (array)$request->get(self::PARAM_TAG_IDS);

		try {
			$article = $this->articleService->edit($id, $title, $tagIds);
		} catch (ValidationException $e) {
			return $this->fail($e);
		}

		return $this->success(new ArticleMapper($article));
	}
}

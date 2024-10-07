<?php

namespace App\Controller\Tag;

use App\Mapper\TagMapper;
use App\Service\TagService;
use App\Exception\ValidationException;
use App\Controller\AbstractJsonController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Create tag
 */
class CreateController extends AbstractJsonController
{
	/**
	 * Param "name of tag"
	 */
	private const string PARAM_NAME = 'name';

	/**
	 * Constructor
	 *
	 * @param TagService $tagService Tag service
	 */
	public function __construct(private readonly TagService $tagService)
	{
	}

	#[Route('/tag', name: 'app_tag_create', methods: ['PUT'])]
	public function __invoke(Request $request): JsonResponse
	{
		try {
			$tag = $this->tagService->create((string)$request->get(self::PARAM_NAME));
		} catch (ValidationException $e) {
			return $this->fail($e);
		}

		return $this->success(new TagMapper($tag));
	}
}

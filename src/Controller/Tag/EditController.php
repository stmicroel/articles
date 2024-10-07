<?php

namespace App\Controller\Tag;

use App\Controller\AbstractJsonController;
use App\Exception\ValidationException;
use App\Mapper\TagMapper;
use App\Service\TagService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Edit tag
 */
class EditController extends AbstractJsonController
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
	public function __construct(private TagService $tagService)
	{
	}

	#[Route('/tag/{id}/edit', name: 'app_tag_edit', methods: ['PATCH'])]
	public function __invoke(Request $request, ?int $id): JsonResponse
	{
		try {
			$tag = $this->tagService->edit(
				(int)$id,
				(string)$request->get(self::PARAM_NAME),
			);
		} catch (ValidationException $e) {
			return $this->fail($e);
		}

		return $this->success(new TagMapper($tag));
	}
}

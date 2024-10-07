<?php

namespace App\Controller;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Abstract base controller for define base return methods
 */
abstract class AbstractJsonController extends AbstractController
{
	/**
	 * Success response
	 *
	 * @param mixed $result Result
	 * @return JsonResponse
	 */
	protected function success(mixed $result = []): JsonResponse {
		return $this->json([
			'success' => true,
			'data' => $result,
		]);
	}

	/**
	 * Fail response
	 *
	 * @param mixed $errors Errors
	 * @return JsonResponse
	 */
	protected function fail(mixed $errors): JsonResponse {
		if ($errors instanceof ValidationException) {
			$errors = $errors->getMessage();
		}

		return $this->json([
			'success' => false,
			'error' => $errors,
		]);
	}
}
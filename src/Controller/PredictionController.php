<?php

namespace App\Controller;

use App\Service\PredictionService;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PredictionController extends AbstractController
{
    private $predictionService;
    private $validationService;
    private const REQUIRED_FIELDS = ['prediction', 'event_id', 'market_type'];

    public function __construct(PredictionService $predictionService, ValidationService $validationService)
    {
        $this->predictionService = $predictionService;
        $this->validationService = $validationService;
    }

    /**
     * @param  $id
     * @return JsonResponse
     */
    public function showAction(int $id): JsonResponse
    {
        $prediction = $this->predictionService->find($id);

        if (!$prediction) {
            return new JsonResponse('No prediction found under id ' . $id, JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($prediction);
    }

    /**
     * @return JsonResponse
     */
    public function showAllAction(): JsonResponse
    {
        $predictions = $this->predictionService->findAll();

        return new JsonResponse($predictions);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $valid = $this->validationService->validateBody($data);

        if ($valid['success'] === false) {
            if (isset($valid['missingField'])) {
                return new JsonResponse(
                    'Field ' . $valid['missingField'] . ' is mandatory',
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }

            if (isset($valid['wrongType'])) {
                return new JsonResponse(
                    'Field ' . $valid['wrongType'] . ' must be a number',
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        $this->predictionService->createAction($data);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */

    public function updateAction(int $id, Request $request): JsonResponse
    {
        $prediction = $this->predictionService->find($id);

        if (!$prediction) {
            return new JsonResponse('No prediction found under id ' . $id, JsonResponse::HTTP_NOT_FOUND);
        }

        foreach (self::REQUIRED_FIELDS as $field) {
            if ($request->request->has($field) && empty($request->request->get($field))) {
                return new
                JsonResponse('Field ' . $field . ' can not be empty.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        if ($request->request->has('event_id') && !is_numeric($request->request->get('event_id'))) {
            return new JsonResponse('Field event_id must be a number', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $request->request->all();

        $this->predictionService->updateAction($id, $data);

        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction(int $id): JsonResponse
    {
        $prediction = $this->predictionService->find($id);

        if (!$prediction) {
            return new JsonResponse('No prediction found under id ' . $id, JsonResponse::HTTP_NOT_FOUND);
        }

        $this->predictionService->deleteAction($id);

        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }


    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStatusAction(int $id, Request $request): JsonResponse
    {
        $prediction = $this->predictionService->find($id);

        if (!$prediction) {
            return new JsonResponse('No prediction found under id ' . $id, JsonResponse::HTTP_NOT_FOUND);
        }

        $status = $request->get('status');

        if (!$this->validationService->isStatusValid($status)) {
            return new JsonResponse('Status value is not correct.', JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->predictionService->updateStatusAction($id, $status);

        return new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
    }
}

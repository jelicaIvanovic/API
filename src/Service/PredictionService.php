<?php

namespace App\Service;

use App\Entity\Prediction;
use App\Repository\PredictionRepository;

class PredictionService
{

    private $predictionRepository;

    public function __construct(PredictionRepository $predictionRepository)
    {
        $this->predictionRepository = $predictionRepository;
    }

    public function findAll(): array
    {
        return $this->predictionRepository->findAll();
    }

    public function find(int $id): ?Prediction
    {
        return $this->predictionRepository->find($id);
    }

    public function createAction(array $data): void
    {
         $this->predictionRepository->save($data);
    }

    public function updateAction(int $id, array $data): void
    {
        $this->predictionRepository->update($id, $data);
    }

    public function updateStatusAction(int $id, string $status): void
    {
        $this->predictionRepository->updateStatus($id, $status);
    }

    public function deleteAction(int $id): void
    {
        $this->predictionRepository->delete($id);
    }
}

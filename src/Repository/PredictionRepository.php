<?php

namespace App\Repository;

use App\Entity\Prediction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Prediction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prediction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prediction[]    findAll()
 * @method Prediction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PredictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prediction::class);
    }

    public function save(array $data): void
    {
        $predictionModel = new Prediction();
        $predictionModel->setMarketType($data['market_type']);
        $predictionModel->setPrediction($data['prediction']);
        $predictionModel->setEventId($data['event_id']);

        $this->getEntityManager()->persist($predictionModel);
        $this->getEntityManager()->flush();
    }

    public function update(int $id, array $data): void
    {
        $predictionModel = $this->find($id);
        if (isset($data['market_type'])) {
            $predictionModel->setMarketType($data['market_type']);
        }
        if (isset($data['prediction'])) {
            $predictionModel->setPrediction($data['prediction']);
        }
        if (isset($data['event_id'])) {
            $predictionModel->setEventId($data['event_id']);
        }
        $this->getEntityManager()->flush();
    }

    public function updateStatus(int $id, string $status): void
    {
        $predictionModel = $this->find($id);
        $predictionModel->setStatus($status);
        $this->getEntityManager()->flush();
    }


    public function delete(int $id): void
    {
        $predictionModel = $this->find($id);
        $this->getEntityManager()->remove($predictionModel);
        $this->getEntityManager()->flush();
    }
}

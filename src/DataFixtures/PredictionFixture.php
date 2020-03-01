<?php

namespace App\DataFixtures;

use App\Entity\Prediction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PredictionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $marketTypeArr = ['correct_score', '1x2'];
        $predictionStatusArr = ['win', 'unresolved', 'lost'];
        $predictionTypeArr = [
            '1x2' => ['1', 'X', '2'],
            'correct_score' => ['1:0', '1:1', '3:2', '4:1', '1:6', '0:1', '0:3']
        ];


        for ($i = 0; $i < 3; $i++) {
            $marketType = $this->getRandomElementFromArray($marketTypeArr);
            $status = $this->getRandomElementFromArray($predictionStatusArr);
            $type = $this->getRandomElementFromArray($predictionTypeArr[$marketType]);
            $prediction = new Prediction();
            $prediction->setEventId($i);
            $prediction->setStatus($status);
            $prediction->setMarketType($marketType);
            $prediction->setPrediction($type);

            $manager->persist($prediction);
        }

        $manager->flush();
    }


    private function getRandomElementFromArray(array $data): string
    {
        $randKey = array_rand($data, 1);

        return $data[$randKey];
    }
}

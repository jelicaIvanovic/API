<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ValidationService
{
    private const STATUSES = ['lost', 'win', 'unresolved'];
    private const REQUIRED_FIELDS = ['prediction', 'event_id', 'market_type'];

    public function isStatusValid(string $status): bool
    {
        return in_array($status, self::STATUSES, true);
    }

    public function validateBody(array $data): array
    {
        $result = ['success' => false];

        foreach (self::REQUIRED_FIELDS as $requiredField) {
            if (!array_key_exists($requiredField, $data)) {
                $result['missingField'] = $requiredField;
                break;
            }

            if ($requiredField === 'event_id' && !is_numeric($data[$requiredField])) {
                $result['wrongType'] = $requiredField;
                break;
            }

            $result = ['success' => false];
        }

        return $result;
    }
}

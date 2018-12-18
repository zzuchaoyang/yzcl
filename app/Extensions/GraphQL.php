<?php

namespace App\Extensions;

use Folklore\GraphQL\Error\ValidationError;
use GraphQL\Error\Error;

class GraphQL
{
    public static function formatError(Error $e)
    {
        if (str_contains($e->getMessage(), 'No query results for model')) {
            $message = '数据已经不存在.('.$e->getMessage().')';
        }

        $error = [
            'message' => $message ?? $e->getMessage(),
        ];

        $locations = $e->getLocations();
        if (!empty($locations)) {
            $error['locations'] = array_map(function ($loc) {
                return $loc->toArray();
            }, $locations);
        }

        // print error traces for local or dev
        if (config('app.env') !== 'production') {
            $error['traces'] = $e->getTraceAsString();
        }

        $previous = $e->getPrevious();
        if ($previous && $previous instanceof ValidationError) {
            $error['validation'] = $previous->getValidatorMessages();
        }

        return $error;
    }
}

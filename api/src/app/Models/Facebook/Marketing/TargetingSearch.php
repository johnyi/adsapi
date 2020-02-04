<?php

namespace App\Models\Facebook\Marketing;

use App\Models\Facebook\Marketing;
use FacebookAds\Object\TargetingSearch as FacebookTargetingSearch;

class TargetingSearch extends Marketing
{
    public function search(string $type, string $class = null, string $query = null, array $params = [])
    {
        $response = [];

        $cursor = FacebookTargetingSearch::search($type, $class, $query, $params);

        while ($cursor->key() !== null) {
            $response[] = $cursor->current()->getData();

            $cursor->next();
        }

        return $response;
    }
}

<?php

namespace App\Services;

class Slugger
{
    public static function make(string $value): string
    {
        $map = [
            'а' => 'a','б' => 'b','в' => 'v','г' => 'g','д' => 'd','е' => 'e','ё' => 'e','ж' => 'zh','з' => 'z','и' => 'i','й' => 'y',
            'к' => 'k','л' => 'l','м' => 'm','н' => 'n','о' => 'o','п' => 'p','р' => 'r','с' => 's','т' => 't','у' => 'u','ф' => 'f',
            'х' => 'h','ц' => 'ts','ч' => 'ch','ш' => 'sh','щ' => 'sch','ъ' => '','ы' => 'y','ь' => '','э' => 'e','ю' => 'yu','я' => 'ya',
        ];

        $value = mb_strtolower($value);
        $value = strtr($value, $map);
        $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?: '';
        return trim($value, '-') ?: 'n-a';
    }
}

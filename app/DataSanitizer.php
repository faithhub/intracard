<?php

namespace App;

trait DataSanitizer
{
    protected function sanitizeData($data)
    {
        if (is_array($data)) {
            return array_map(function ($item) {
                return $this->sanitizeData($item);
            }, $data);
        }

        if (is_string($data)) {
            return trim(
                preg_replace('/[\x00-\x1F\x7F-\x9F\x{200B}-\x{200D}\x{FEFF}]/u', '', 
                str_replace(['"', "'"], '', $data))
            );
        }

        return $data;
    }
}

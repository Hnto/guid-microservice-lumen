<?php

namespace App\Core\Traits;

trait FillClassProperties
{

    public function fill(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }
}

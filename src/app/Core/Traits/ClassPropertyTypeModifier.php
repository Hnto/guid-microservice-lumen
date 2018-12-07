<?php
/**
 * Created by PhpStorm.
 * User: herant
 * Date: 22-03-18
 * Time: 15:35
 */

namespace App\Core\Traits;


trait ClassPropertyTypeModifier
{

    private $toInt = 'toInt';
    private $toString = 'toString';

    public function modify()
    {
        foreach ($this->modifiers as $type => $values) {
            switch ($type) {
                case $this->toInt:
                    foreach ($values as $key) {
                        $this->{$key} = (int) $this->{$key};
                    }

                    break;
                case $this->toString:
                    foreach ($values as $key) {
                        $this->{$key} = (string) $this->{$key};
                    }
                    break;
            }
        }
    }
}

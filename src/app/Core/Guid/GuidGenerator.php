<?php
/**
 * Created by PhpStorm.
 * User: herant
 * Date: 23-03-18
 * Time: 09:34
 */

namespace App\Core\Guid;


use Ramsey\Uuid\Uuid;

class GuidGenerator
{

    /**
     * Generate a guid
     * The version will
     * be chosen by the guid spec
     *
     * @return string
     */
    public static function generateValue()
    {
        switch (GuidSpecs::GUID_VERSION) {
            case 1:
                return Uuid::uuid1()->toString();
            case 4:
            default:
                return Uuid::uuid4()->toString();
        }
    }
}

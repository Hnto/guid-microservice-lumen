<?php

namespace App\Core\Token;

use App\Core\Traits\ClassPropertyTypeModifier;
use App\Core\Traits\FillClassProperties;
use Carbon\Carbon;

class Token
{

    use FillClassProperties;
    use ClassPropertyTypeModifier;

    /**
     * Contains an array of
     * keys that must be
     * modified to their
     * required types
     *
     * @var array
     */
    protected $modifiers = [
        'toInt' => ['id'],
        'toString' => ['value', 'ends_at']
    ];

    /**
     * Contains the id
     *
     * @var int
     */
    private $id;

    /**
     * Contains the token value
     *
     * @var string
     */
    private $value;

    /**
     * Contains the ends at
     *
     * @var string
     */
    private $ends_at;

    /**
     * Get the token id
     *
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->id;
    }

    /**
     * Get the token value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the ends at date time
     *
     * @return string
     */
    public function getEndsAt(): string
    {
        return $this->ends_at;
    }

    /**
     * Check whether this token
     * is stil valid or not
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        if (empty($this->getEndsAt())) {
            return true;
        }

        if (!is_string($this->getEndsAt())) {
            return true;
        }

        if (Carbon::parse($this->getEndsAt())->lessThan(Carbon::now())) {
            return true;
        }

        return false;
    }
}

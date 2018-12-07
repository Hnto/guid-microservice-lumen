<?php

namespace App\Core\Guid;


use App\Core\Traits\ClassPropertyTypeModifier;
use App\Core\Traits\FillClassProperties;
use Ramsey\Uuid\Uuid;

class Guid
{

    const GUID_STATUS_ISSUED = 'issued';
    const GUID_STATUS_ASSIGNED = 'assigned';

    use FillClassProperties;
    use ClassPropertyTypeModifier;

    protected $modifiers = [
        'toString' => ['guid', 'assigned_to', 'status', 'created_at']
    ];

    /**
     * Contains the guid
     *
     * @var string
     */
    protected $guid;

    /**
     * Contains the assigned to
     *
     * @var string
     */
    protected $assigned_to;

    /**
     * Contains the status of the guid
     *
     * @var string
     */
    protected $status;

    /**
     * Contains the created at
     *
     * @var string
     */
    protected $created_at;

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @return string
     */
    public function getAssignedTo(): string
    {
        return $this->assigned_to;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Check if the guid
     * has already been
     * assigned to an item
     *
     * @return bool
     */
    public function isAssigned(): bool
    {
        if ($this->getStatus() === self::GUID_STATUS_ASSIGNED) {
            return true;
        }

        return false;
    }

    /**
     * Check if the guid
     * has been issued
     *
     * @return bool
     */
    public function isIssued(): bool
    {
        if ($this->getStatus() === self::GUID_STATUS_ISSUED) {
            return true;
        }

        return false;
    }

    /**
     * Check if a guid is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (empty($this->getGuid())) {
            return false;
        }

        if (!is_string($this->getGuid())) {
            return false;
        }

        if (!Uuid::isValid($this->getGuid())) {
            return false;
        }

        return true;
    }
}

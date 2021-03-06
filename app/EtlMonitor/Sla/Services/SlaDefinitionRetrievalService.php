<?php

namespace App\EtlMonitor\Sla\Services;

use App\EtlMonitor\Common\Services\Service;
use App\EtlMonitor\Sla\Exceptions\InvalidSlaDefinitionTypeException;
use App\EtlMonitor\Sla\Models\AvailabilitySlaDefinition;
use App\EtlMonitor\Sla\Models\DeliverableSlaDefinition;
use App\EtlMonitor\Sla\Models\Interfaces\SlaDefinitionInterface;

class SlaDefinitionRetrievalService extends Service
{

    /**
     * @var string[]
     */
    private $valid_types = [
        'deliverable' => DeliverableSlaDefinition::class,
        'availability' => AvailabilitySlaDefinition::class
    ];

    /**
     * SlaDefinitionRetrievalService constructor.
     * @param string $type
     * @param int $id
     */
    public function __construct(private string $type, private int $id)
    {}

    /**
     * @return SlaDefinitionInterface
     * @throws InvalidSlaDefinitionTypeException
     */
    public function __invoke(): SlaDefinitionInterface
    {
        if (!in_array($this->type, array_keys($this->valid_types))) {
            throw new InvalidSlaDefinitionTypeException($this->type);
        }

        return $this->valid_types[$this->type]::find($this->id);
    }

}

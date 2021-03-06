<?php

namespace App\DwhControl\Etl\Models;

use App\DwhControl\Etl\Models\Abstract\EtlDefinitionAbstract;
use App\DwhControl\Etl\Models\Interfaces\EtlDefinitionInterface;
use App\DwhControl\Etl\Models\Interfaces\EtlExecutionInterface;
use App\DwhControl\Etl\Traits\EtlTypes;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Matchory\Elasticsearch\Query;

class EtlDefinition extends EtlDefinitionAbstract
{
    use EtlTypes;

    /**
     * @param array $attributes
     * @param null $connection
     * @return EtlDefinition
     */
    public function newFromBuilder($attributes = [], $connection = null)
    {
        if (!isset($attributes->type) || get_called_class() !== EtlDefinition::class) {
            return parent::newFromBuilder($attributes, $connection);
        }

        if (is_null($class = static::etl_types()->{$attributes->type}->definition)) {
            throw new \InvalidArgumentException('Invalid ETL type');
        }

        $model = (new $class)->newInstance([], true);

        $model->setRawAttributes((array) $attributes, true);

        $model->setConnection($connection ?: $this->getConnectionName());

        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @param string|null $field
     * @param int|null $limit
     * @return Collection
     */
    public function getExecutions(CarbonInterface $from, CarbonInterface $to, ?string $field = null, ?int $limit = 28): Collection
    {
        // TODO: Implement getExecutions() method.
    }

    /**
     * @param string|null $field
     * @return ?EtlExecutionInterface
     */
    public function getLatestExecution(?string $field = 'date.end_pp'): ?EtlExecutionInterface
    {
        // TODO: Implement getLatestExecution() method.
    }

    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @param null $field
     * @param int $limit
     * @return Collection
     */
    public function getSuccessfulExecutions(CarbonInterface $from, CarbonInterface $to, $field = null, $limit = 28): Collection
    {
        // TODO: Implement getSuccessfulExecutions() method.
    }

}

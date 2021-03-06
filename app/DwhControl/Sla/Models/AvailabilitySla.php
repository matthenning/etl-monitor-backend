<?php

namespace App\DwhControl\Sla\Models;

use App\DwhControl\Sla\Models\Abstract\SlaAbstract;
use App\DwhControl\Sla\Models\Interfaces\SlaInterface;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * App\DwhControl\Sla\Models\DeliverableSla
 *
 * @property int $id
 * @property int $sla_definition_id
 * @property int $timerange_id
 * @property string $timerange_instance_identifier
 * @property string $type
 * @property \Illuminate\Support\Carbon $range_start
 * @property \Illuminate\Support\Carbon $range_end
 * @property int|null $is_open
 * @property string|null $status
 * @property float|null $target_percent
 * @property float|null $achieved_percent
 * @property float|null $progress_percent_intime
 * @property string|null $last_progress_intime_at
 * @property float|null $progress_percent_late
 * @property string|null $last_progress_late_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DwhControl\Common\Models\History[] $history
 * @property-read int|null $history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|DeliverableSlaProgress[] $progress
 * @property-read int|null $progress_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DwhControl\Common\Models\Property[] $properties
 * @property-read int|null $properties_count
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereAchievedPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereIsOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereLastProgressIntimeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereLastProgressLateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereProgressPercentIntime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereProgressPercentLate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereRangeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereRangeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereSlaDefinitionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereTargetPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereTimerangeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereTimerangeInstanceIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $achieved_progress_percent
 * @property float|null $last_progress_percent
 * @property int|null $progress_last_intime_id
 * @property int|null $progress_first_intime_achieved_id
 * @property int|null $progress_last_late_id
 * @property int|null $progress_first_late_achieved_id
 * @property-read DeliverableSlaProgress|null $progress_first_intime_achieved
 * @property-read DeliverableSlaProgress|null $progress_first_late_achieved
 * @property-read DeliverableSlaProgress|null $progress_last_intime
 * @property-read DeliverableSlaProgress|null $progress_last_late
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereAchievedProgressPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereLastProgressPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereProgressFirstIntimeAchievedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereProgressFirstLateAchievedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereProgressLastIntimeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeliverableSla whereProgressLastLateId($value)
 */
class AvailabilitySla extends Sla
{

    /**
     * @var string[]
     */
    protected $attributes = [
        'type' => 'availability'
    ];

    /**
     * @var string
     */
    protected static string $type = 'availability';

    /**
     * @return SlaInterface
     */
    public function fetchProgress(): SlaInterface
    {


        return $this;
    }

    /**
     * @param CarbonInterface|null $time
     * @return SlaInterface
     */
    public function calculate(CarbonInterface $time = null): SlaInterface
    {
        $time = $time ?? Carbon::now();

        if ($this->range_start->gt($time)) {
            $this->setWaiting()->save();
            return $this;
        }

        if ($this->progress_last_intime?->progress_percent >= $this->target_percent) {
            $this->setAchieved($this->progress_last_intime)->setClosed()->save();
            return $this;
        }

        if ($this->range_end->lte($time)) {
            $this->setFailed($this->progress_last_intime)->setClosed()->save();
            return $this;
        }

        if ($this->range_end->gt($time)) {
            $this->setLate()->save();
        }

        $this->setWaiting($this->progress_last_intime)->save();
        return $this;
    }
}

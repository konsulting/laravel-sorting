<?php
namespace Konsulting\Laravel\Sorting;

/**
 * @trait Prioritise
 * Helpers for using a priority column, and allowing promotion/demotion, reordering
 */
trait Prioritise
{
    public static function bootPrioritise()
    {
        static::creating(function ($model) {
            $model->setPriority($model->getMaximumPriority() + 1);
        });
    }

    protected function determinePriorityColumnName()
    {
        if (isset($this->prioritise['column_name'])
            && ! empty($this->prioritise['column_name'])
        ) {
            return $this->prioritise['column_name'];
        }

        return 'priority';
    }

    public function getPriority()
    {
        return $this->{$this->determinePriorityColumnName()};
    }

    public function setPriority($value)
    {
        $this->{$this->determinePriorityColumnName()} = $value;
    }

    public function getMaximumPriority()
    {
        return $this->getPriorityBaseQuery()->max($this->determinePriorityColumnName());
    }

    public function getMinimumPriority()
    {
        return $this->getPriorityBaseQuery()->min($this->determinePriorityColumnName());
    }

    /* override this if you need to specify other restrictions */
    public function getPriorityBaseQuery()
    {
        return static::whereRaw('1');
    }

    public function swapPriorityWith($model)
    {
        if (is_integer($model)) {
            $model = $this->getPriorityBaseQuery()->findOrFail($model);
        }

        if ($model->id == $this->id) {
            return;
        }

        $startingAt = $this->getPriority();

        $this->setPriority($model->getPriority());
        $this->save();
        $model->setPriority($startingAt);
        $model->save();
    }

    public function promote()
    {
        $swapWith = $this->getPriorityBaseQuery()->where('priority', '<', $this->getPriority())
            ->orderBy('priority', 'desc')->take(1)->first();

        $this->swapPriorityWith($swapWith);
    }

    public function demote()
    {
        $swapWith = $this->getPriorityBaseQuery()->where('priority', '>', $$this->getPriority())
            ->orderBy('priority', 'desc')->take(1)->first();

        $this->swapPriorityWith($swapWith);
    }

    public function scopePrioritise($scope)
    {
        return $scope->orderBy($this->determinePriorityColumnName(), 'asc');
    }

    public function promoteToTop()
    {
        $minimumPriority = $this->getMinimumPriority();

        $this->getPriorityBaseQuery()->update([$this->determinePriorityColumnName() => \DB::raw($this->determinePriorityColumnName() . ' + 1')]);

        $this->setPriority($minimumPriority);
        $this->save();
    }

    public function demoteToBottom()
    {
        $maximumPriority = $this->getMaximumPriority();

        $this->getPriorityBaseQuery()->update([$this->determinePriorityColumnName() => \DB::raw($this->determinePriorityColumnName() . ' - 1')]);

        $this->setPriority($maximumPriority);
        $this->save();
    }
}

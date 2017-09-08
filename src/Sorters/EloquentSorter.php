<?php

namespace Konsulting\Laravel\Sorting\Sorters;

use Illuminate\Database\Eloquent\Builder;

class EloquentSorter extends Sorter
{
    /**
     * @param Builder $builder
     * @param array   $sort
     * @return mixed|void
     * @throws \Exception
     */
    public function sort(Builder $builder, $sort = [])
    {
        $this->sorting = $this->parseInstructions(
            empty($sort) ? $this->getSortRequest() : $sort
        );

        $model = $builder->getModel();
        $modelTable = $model->getTable();
        $joins = collect($builder->getQuery()->joins);

        foreach ($this->sorting as $item) {
            if ('none' === ($order = $item->getOrder())) {
                continue;
            }

            list($relation, $column) = $item->getRelationAndColumn();

            if (empty($relation)) {
                $builder->orderBy($modelTable . '.' . $column, $order);
                continue;
            }

            $join = $joins->first(function ($item) use ($relation) {
                return $item->table == $relation;
            });

            if (empty($join)) {
                throw new \Exception('Please set up the correct join for ' . $relation);
            }

            $builder->orderBy($relation . '.' . $column, $order);
        }
    }
}

<?php

namespace Konsulting\Laravel\Sorting\Sorters;

class EloquentSorter extends Sorter
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param null $sort
     *
     * @throws \Exception
     */
    public function sort($builder, $sort = null)
    {
        $this->sorting = $this->parseInstructions(
            (is_null($sort) || empty($sort)) ? $this->getSortRequest() : $sort
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

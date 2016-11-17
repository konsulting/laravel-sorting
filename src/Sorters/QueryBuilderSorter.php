<?php

namespace Konsulting\Laravel\Sorting\Sorters;

class QueryBuilderSorter extends Sorter
{
    /**
     * @param \Illuminate\Database\Query\Builder  $builder
     * @param null $sort
     *
     * @throws \Exception
     */
    public function sort($builder, $sort = null)
    {
        $this->sorting = $this->parseInstructions(
            (is_null($sort) || empty($sort)) ? $this->getSortRequest() : $sort
        );

        $modelTable = $builder->from;
        $joins = collect($builder->joins);

        foreach ($this->sorting as $item) {
            if ('none' === ($order = $item->getOrder())) {
                continue;
            }

            list($relation, $column) = $item->getRelationAndColumn();

            if (empty($relation)) {
                $builder->orderBy($modelTable . '.' . $column, $order);
                continue;
            }

            $join = $joins->first(function ($key, $item) use ($relation) {
                return $item->table == $relation;
            });

            if (empty($join)) {
                throw new \Exception('Please set up the correct join for ' . $relation);
            }

            $builder->orderBy($relation . '.' . $column, $order);
        }
    }
}

<?php

namespace Konsulting\Laravel\Sorting\Sorters;

class SqlSorter extends Sorter
{
    /**
     * @param   $builder
     * @param null $sort
     *
     * @throws \Exception
     */
    public function sort($builder, $sort = null)
    {
        throw new \Exception('Sort method is not appropriate for Sql sorter. Use toSql instead.');
    }

    public function toSql($modelTable, $sort = null)
    {
        $this->sorting = $this->parseInstructions(
            (is_null($sort) || empty($sort)) ? $this->getSortRequest() : $sort
        );

        $sql = [];
        //we will skip checking joins as we assume they're working in Sql directly

        foreach ($this->sorting as $item) {
            if ('none' === ($order = $item->getOrder())) {
                continue;
            }

            list($relation, $column) = $item->getRelationAndColumn();

            if (empty($relation)) {
                $sql[] = $modelTable . '.' . $column . ' ' . $order;
                continue;
            }

            $sql[] = $relation . ' ' . $order;
        }

        return ! empty($sql) ? 'ORDER BY ' . implode(', ', $sql) : '';
    }
}

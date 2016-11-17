<?php

namespace Konsulting\Laravel\Sorting;

use Illuminate\Database\Eloquent\Builder;
use Konsulting\Laravel\Sorting\Sorters\EloquentSorter;

trait Sortable
{
    protected static $sorter;

    public static function bootSortable()
    {
        $settings = property_exists(static::class, 'sortableSettings')
            ? static::$sortableSettings
            : [];

        static::$sorter = new EloquentSorter($settings);
    }

    public function setSortParameterName($name)
    {
        static::$sorter->setSortParamterName($name);
    }

    public function scopeSort(Builder $builder, $sort = null)
    {
        static::$sorter->sort($builder, $sort);
    }

    public static function sortableLink($col, $title = null, $attributes = [])
    {
        return static::$sorter->sortableLink($col, $title, $attributes);
    }
}

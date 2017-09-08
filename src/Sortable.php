<?php

namespace Konsulting\Laravel\Sorting;

use Illuminate\Database\Eloquent\Builder;
use Konsulting\Laravel\Sorting\Sorters\EloquentSorter;
use Konsulting\Laravel\Sorting\Sorters\Sorter;

trait Sortable
{
    /**
     * @var Sorter
     */
    protected static $sorter;

    /**
     * Register the sortable settings. This method is automatically called by Eloquent.
     *
     * @return void
     */
    public static function bootSortable()
    {
        $settings = property_exists(static::class, 'sortableSettings')
            ? static::$sortableSettings
            : [];

        static::$sorter = new EloquentSorter($settings);
    }

    /**
     * Set the sort parameter name.
     *
     * @param string $name
     * @return void
     */
    public function setSortParameterName($name)
    {
        static::$sorter->setSortParameterName($name);
    }

    /**
     * Register the sort scope.
     *
     * @param Builder $builder
     * @param null    $sort
     * @return void
     */
    public function scopeSort(Builder $builder, $sort = null)
    {
        static::$sorter->sort($builder, $sort);
    }

    /**
     * @param       $col
     * @param null  $title
     * @param array $attributes
     * @return \Illuminate\Support\HtmlString|null|string
     */
    public static function sortableLink($col, $title = null, $attributes = [])
    {
        return static::$sorter->sortableLink($col, $title, $attributes);
    }
}

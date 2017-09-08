<?php

namespace Konsulting\Laravel\Sorting\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Konsulting\Laravel\Sorting\SortItem;

abstract class Sorter
{
    protected $settings = [
        'sortable'          => [],
        'sortParameterName' => 'sort',
        'defaultSort'       => null,
    ];
    protected $sortable;
    protected $sortParameterName;
    protected $sorting;

    /**
     * Merge settings and initialise sorting parameter.
     *
     * @param array $settings
     */
    public function __construct($settings = [])
    {
        $this->settings = collect($this->settings)->merge($settings);

        $this->sortable = (array) $this->settings->get('sortable');
        $this->sortParameterName = $this->settings->get('sortParameterName');

        $this->sorting = collect([]);
    }

    /**
     * Set the parameter name used for sorting.
     *
     * @param string $name
     */
    public function setSortParameterName($name)
    {
        $this->sortParameterName = $name;
    }

    /**
     * Perform the sort.
     *
     * @param Builder $builder
     * @param null    $sort
     * @return mixed
     */
    abstract public function sort(Builder $builder, $sort = null);

    /**
     * Get the sort parameter name if it has been sent through in the request. If not, get it from the settings or
     * failing that use an empty array.
     *
     * @return string
     */
    protected function getSortRequest()
    {
        return request()->get($this->sortParameterName, $this->settings->get('defaultSort', []));
    }

    /**
     * Parse sort instructions and
     *
     * @param string $sort
     * @return Collection
     */
    protected function parseInstructions($sort = null)
    {
        if (is_null($sort)) {
            return collect([]);
        }

        $sort = explode(',', $sort);

        $result = [];
        foreach ($sort as $field) {
            $item = new SortItem($field);

            if ($this->isSortable($item->getField())) {
                $result[] = $item;
            }
        }

        return collect($result);
    }

    /**
     * Check if a given key is sortable.
     *
     * @param string $key
     * @return bool
     */
    public function isSortable($key)
    {
        return (bool) in_array($key, $this->sortable);
    }

    /**
     * Return a link that can be used to trigger a sort.
     *
     * @param       $col
     * @param null  $title
     * @param array $attributes
     * @return HtmlString|null|string
     */
    public function sortableLink($col, $title = null, $attributes = [])
    {
        if ( ! $this->isSortable($col)) {
            return $title;
        }

        $title = is_null($title) ? ucfirst(str_singular(str_replace('_', ' ', $col))) : $title;

        $item = $this->sorting->first(function ($item) use ($col) {
            return $item->getField() == $col;
        });

        $indicator = empty($item) ? '' : $item->getArrow();
        $parameters = $this->buildLinkParameters($col);

        $attributeString = ! empty($attributes['class'])
            ? ' class="' . htmlspecialchars($attributes['class']) . '"'
            : '';

        $fullUrl = url()->current() . "?" . http_build_query($parameters);

        return new HtmlString("<a href=\"{$fullUrl}\"{$attributeString}>{$title} {$indicator}</a>");
    }

    /**
     * @param $col
     * @return array
     */
    protected function buildLinkParameters($col)
    {
        foreach ($this->sorting as $item) {
            if ($item->getField() == $col) {
                $colItem = $item;
                continue;
            }

            $result[$item->getField()] = $item->getUrlPart();
        }

        $colItem = isset($colItem) ? $colItem : new SortItem($col, '');
        $result[$col] = $colItem->getNext()->getUrlPart();

        return array_merge(request()->all(), [
            $this->sortParameterName => implode(',', $result)
        ]);
    }
}

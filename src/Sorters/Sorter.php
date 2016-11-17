<?php

namespace Konsulting\Laravel\Sorting\Sorters;

use Konsulting\Laravel\Sorting\SortItem;
use Illuminate\Support\HtmlString;

abstract class Sorter
{
    protected $settings = [
        'sortable' => [],
        'sortParameterName' => 'sort',
        'defaultSort' => null,
    ];
    protected $sortable;
    protected $sortParameterName;
    protected $sorting;

    public function __construct($settings = [])
    {
        $this->settings = collect($this->settings)->merge($settings);

        $this->sortable = (array) $this->settings->get('sortable');
        $this->sortParameterName = $this->settings->get('sortParameterName');

        $this->sorting = collect([]);
    }

    public function setSortParameterName($name)
    {
        $this->sortParameterName = $name;
    }

    abstract public function sort($builder, $sort = null);

    protected function getSortRequest()
    {
        return request()->get($this->sortParameterName, $this->settings->get('defaultSort', null));
    }

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

    public function isSortable($key)
    {
        return (bool) in_array($key, $this->sortable);
    }

    public function sortableLink($col, $title = null, $attributes = []) {
        if (! $this->isSortable($col)) {
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

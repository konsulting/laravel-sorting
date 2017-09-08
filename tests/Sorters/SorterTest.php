<?php

namespace Konsulting\Laravel\Sorting\Tests\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Konsulting\Laravel\Sorting\Sorters\Sorter as ParentSorter;
use Konsulting\Laravel\Sorting\SortItem;
use Konsulting\Laravel\Sorting\Tests\TestCase;

class SorterTest extends TestCase
{
    /**
     * @var Sorter
     */
    protected $sorter;

    public function setUp()
    {
        parent::setUp();

        $this->sorter = new Sorter([
            'sortable' => ['name', 'email', 'date_of_birth'],
        ]);
    }

    /** @test */
    public function it_holds_the_sortable_settings()
    {
        $settings = $this->sorter->getSettings()->toArray();
        $expectedSettings = ['sortable', 'sortParameterName', 'defaultSort'];

        $this->assertEquals($expectedSettings, array_keys($settings));
    }

    /** @test */
    public function it_allows_settings_to_be_overridden()
    {
        $expectedSettings = [
            'sortable'          => ['date_of_birth', 'email'],
            'sortParameterName' => 'sort',
            'defaultSort'       => 'desc',
        ];
        $sorter = new Sorter($expectedSettings);
        $settings = $sorter->getSettings()->toArray();

        $this->assertEquals($expectedSettings, $settings);
    }

    /** @test */
    public function it_allows_the_sortable_name_field_to_be_set()
    {
        $this->sorter->setSortParameterName('email');
        $name = $this->sorter->getSortParameterName();

        $this->assertEquals('email', $name);
    }

    /** @test */
    public function it_returns_an_empty_collection_when_parsing_instructions_if_no_sort_parameters_are_given()
    {
        $result = $this->sorter->parseInstructions();

        $this->assertEquals(Collection::make(), $result);
    }

    /** @test */
    public function it_returns_a_collection_of_sort_items_if_they_are_passed_in_and_specified_as_sortable()
    {
        $result = $this->sorter->parseInstructions('name,+email,-not_a_sortable_field');

        $expected = Collection::make([new SortItem('name'), new SortItem('+email')]);

        $this->assertEquals($expected, $result);
    }
}

class Sorter extends ParentSorter
{
    public function sort(Builder $builder, $sort = null)
    {
    }

    public function parseInstructions($sort = '')
    {
        return parent::parseInstructions($sort);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSortParameterName()
    {
        return $this->sortParameterName;
    }
}

<?php

namespace Konsulting\Laravel\Sorting\Tests\Sorters;

use Illuminate\Database\Eloquent\Builder;
use Konsulting\Laravel\Sorting\Sorters\Sorter as ParentSorter;
use Konsulting\Laravel\Sorting\Tests\TestCase;

class SorterTest extends TestCase
{
    /**
     * @var Sorter
     */
    protected $sorter;

    public function setUp()
    {
        $this->sorter = new Sorter;
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
}

class Sorter extends ParentSorter
{
    public function sort(Builder $builder, $sort = null)
    {
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

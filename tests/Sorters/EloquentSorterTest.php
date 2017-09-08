<?php

namespace Konsulting\Laravel\Sorting\Tests\Sorters;

use Konsulting\Laravel\Sorting\Sorters\EloquentSorter;
use Konsulting\Laravel\Sorting\Tests\TestCase;

class EloquentSorterTest extends TestCase
{
    /**
     * @var EloquentSorter
     */
    protected $sorter;

    public function setUp()
    {
        parent::setUp();

        $this->sorter = new EloquentSorter;
    }

    /** @test */
    public function it_sorts_eloquent_models()
    {
        $this->assertTrue(true);
    }
}

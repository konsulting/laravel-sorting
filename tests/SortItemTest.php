<?php

namespace Konsulting\Laravel\Sorting\Tests;

use Konsulting\Laravel\Sorting\SortItem;

class SortItemTest extends TestCase
{
    /** @test */
    public function sort_order_can_be_specified_as_part_of_the_field_name()
    {
        $item = new SortItem('+name');

        $this->assertEquals('name', $item->getField());
        $this->assertEquals('asc', $item->getOrder());
    }

    /** @test */
    public function sort_order_can_be_specified_as_a_separate_argument()
    {
        $item = new SortItem('name', 'asc');

        $this->assertEquals('name', $item->getField());
        $this->assertEquals('asc', $item->getOrder());
    }

    /** @test */
    public function sort_order_is_optional()
    {
        $item = new SortItem('name');

        $this->assertEquals('name', $item->getField());
        $this->assertEquals('none', $item->getOrder());
    }

    /** @test */
    public function a_sort_item_can_be_specified_with_the_from_string_method()
    {
        $item = (new SortItem(null))->fromString('-name');

        $this->assertEquals('name', $item->getField());
        $this->assertEquals('desc', $item->getOrder());
    }

    /** @test */
    public function the_sort_order_may_be_specified_in_a_number_of_different_formats()
    {
        $sortInstructions = [
            'asc'  => ['+name', 'name asc'],
            'desc' => ['-name', 'name desc'],
            'none' => ['name'],
        ];

        foreach ($sortInstructions as $order => $instructions) {
            foreach ($instructions as $instruction) {
                $this->assertEquals($order, (new SortItem($instruction))->getOrder());
            }
        }
    }

    /** @test */
    public function it_gets_the_sort_order_arrow()
    {
        $ascendingArrow = (new SortItem('+name'))->getArrow();
        $descendingArrow = (new SortItem('-name'))->getArrow();
        $noSortArrow = (new SortItem('name'))->getArrow();

        $this->assertEquals('&uarr;', $ascendingArrow);
        $this->assertEquals('&darr;', $descendingArrow);
        $this->assertEquals('', $noSortArrow);
    }

    /** @test */
    public function it_gets_the_url_indicator()
    {
        $ascending = (new SortItem('+name'))->getUrlPart();
        $descending = (new SortItem('-name'))->getUrlPart();
        $noSort = (new SortItem('name'))->getUrlPart();

        $this->assertEquals('+name', $ascending);
        $this->assertEquals('-name', $descending);
        $this->assertEquals('name', $noSort);
    }

    /** @test */
    public function it_returns_the_field_name_and_order_as_an_array()
    {
        $item = new SortItem('+name');

        $this->assertEquals(['name', 'asc'], $item->getArrayPair());
    }

    /** @test */
    public function it_increments_the_sort_order_and_returns_a_new_sort_item()
    {
        $item = new SortItem('+name');

        $nextSortOrderItem = $item->getNext();
        $this->assertEquals('desc', $nextSortOrderItem->getOrder());

        $nextSortOrderItem = $nextSortOrderItem->getNext();
        $this->assertEquals('none', $nextSortOrderItem->getOrder());

        $nextSortOrderItem = $nextSortOrderItem->getNext();
        $this->assertEquals('asc', $nextSortOrderItem->getOrder());
    }
}

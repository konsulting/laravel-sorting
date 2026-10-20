<?php

namespace Konsulting\Laravel\Sorting\Tests;

use Konsulting\Laravel\Sorting\Tests\TestSupport\EloquentTestServiceProvider;
use Konsulting\Laravel\Sorting\Tests\TestSupport\User;
use Konsulting\Laravel\Sorting\Tests\TestSupport\UsersSeeder;


class SortableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed', ['--class' => UsersSeeder::class]);
    }


    protected function getPackageProviders($app)
    {
        return [EloquentTestServiceProvider::class];
    }

    /** @test */
    public function it_sorts_eloquent_models_by_specified_column()
    {
        $expectedNames = User::all()->pluck('name')->toArray();
        sort($expectedNames);

        $sortedNames = User::sort('+name')->pluck('name')->toArray();
        $descendingSortedNames = User::sort('-name')->pluck('name')->toArray();

        $this->assertEquals($expectedNames, $sortedNames);
        $this->assertEquals(array_reverse($expectedNames), $descendingSortedNames);
    }

    /** @test */
    public function it_sorts_by_a_date_column()
    {
        $expectedDates = User::all()->pluck('date_of_birth')->toArray();
        sort($expectedDates);

        $sortedDates = User::sort('+date_of_birth')->pluck('date_of_birth')->toArray();

        $this->assertEquals($expectedDates, $sortedDates);
    }

    /** @test */
    public function it_reads_the_sort_order_from_the_request()
    {
        request()->query->set('sort', '-name');

        $expectedNames = User::all()->pluck('name')->sort()->reverse()->values()->toArray();

        $this->assertEquals($expectedNames, User::sort()->pluck('name')->toArray());
    }

    /** @test */
    public function it_falls_back_to_the_default_sort_when_the_request_sort_is_not_a_string()
    {
        request()->query->set('sort', ['name']);

        $expectedNames = User::all()->pluck('name')->sort()->values()->toArray();

        $this->assertEquals($expectedNames, User::sort()->pluck('name')->toArray());
    }

    public function it_sorts_by_multiple_columns()
    {

    }

}

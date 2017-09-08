<?php

namespace Konsulting\Laravel\Sorting\Tests\TestSupport;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users = collect([
            'robin' => [
                'name'          => 'Robin',
                'email'         => 'robin@klever.co.uk',
                'date_of_birth' => Carbon::create(1993, 11, 9),
                'password'      => bcrypt('password'),
            ],

            'keoghan' => [
                'name'          => 'Keoghan',
                'email'         => 'keoghan@klever.co.uk',
                'date_of_birth' => Carbon::create(1900, 3, 2),
                'password'      => bcrypt('password'),
            ],

            'roger' => [
                'name'          => 'Roger',
                'email'         => 'roger@klever.co.uk',
                'date_of_birth' => Carbon::create(1960, 4, 1),
                'password'      => bcrypt('password'),
            ]
        ]);

        $users->each(function ($user) {
            User::create($user);
        });
    }
}

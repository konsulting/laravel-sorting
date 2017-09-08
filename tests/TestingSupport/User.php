<?php

namespace Konsulting\Laravel\Sorting\Tests\TestSupport;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Konsulting\Laravel\Sorting\Sortable;

class User extends EloquentModel
{
    use Sortable;

    protected static $sortableSettings = [
        'sortable'    => ['name', 'email', 'date_of_birth', 'created_at', 'updated_at'],
        'defaultSort' => '+name',
    ];
}

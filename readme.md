# Sorting

*A set of utilities for sorting models in [Laravel](https://laravel.com).*

## Installation

Install using composer: `composer require konsulting/laravel-sorting`

## Usage

### Sorting

* Add the `Konsulting\Laravel\Sorting\Sortable` trait to your model.
* Set up the configuration for the model by adding the `$sortableSettings` property providing the allowed sortable fields and a default sort order.
```php 
protected static $sortableSettings = [
    'sortable' => ['name', 'created_at', 'updated_at'],
    'defaultSort' => '+name',
];
```
* In your view where you’d like to add a sortable link, include the following (example for a Post model):
```php
{{ App\Post::sortableLink('name', 'Name') }}
```
* When you want retrive a sorted collection of models, use the `sort()` method. 
```php
App\Post::sort()->paginate();
// The sort method will extract the sort variable from the request, unless you pass them through (e.g. if you store in the session).
```

### Prioritise

* Add the `Konsulting\Laravel\Sorting\Prioritise` trait to your model.

* Ensure your model has a column for 'priority'. By default, this is called 'priority' but can be overriden if needed by overriding the `determinePriorityColumnName()` method in the model.

* Use the `prioritise` scope to sort the retrieved results by priority.

## Security

If you find any security issues, or have any concerns, please email [keoghan@klever.co.uk](keoghan@klever.co.uk), rather than using the issue tracker.

## Contributing

Contributions are welcome and will be fully credited. We will accept contributions by Pull Request. 

Please:

* Use the PSR-2 Coding Standard
* Add tests, if you’re not sure how, please ask.
* Document changes in behaviour, including readme.md.

## Testing
We use [PHPUnit](https://phpunit.de). 

Run tests using PHPUnit: `vendor/bin/phpunit`

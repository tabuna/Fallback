# Actions for Failback

Actions for Failback is a lightweight wrapper that empowers you to handle unavailability gracefully.

## Installation

To install the package, you can use Composer. Simply run the following command:

``` bash
$ composer require tabuna/failback
```

## Usage

#### Setting a Default Value:

You can create an action with a default value using the following code:

```php
use Tabuna\FailBack\Action;

// $result = 'default';
$result = Action::make(function () {
    throw new \Exception();
}, 'default')->run();

// Alternatively, you can use the short helper
$result = failBack(function () {
    throw new \Exception();
}, 'default')();
```

#### Adding Fallback Actions:

You can define multiple fallback actions using the `fail` method:

```php
// $result = true;
$result = failBack(function () {
    throw new \Exception();
})->fail(function () {
    throw new \Error();
})->fail(function () {
    return true;
})();
```

#### Utilizing Classes:

To specify a fallback action using a class, create an anonymous class with an `__invoke` method:

```php
$class = new class {

    /**
     * @return bool
     */
    public function __invoke(): bool
    {
        return true;
    }
};

// $result = true;
$result = failBack(function () {
    throw new Exception();
})->fail($class)->run();
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# Actions for Failback

Actions for Failback is a small wrapper that allows you to create a branch of inaccessibility.

## Install

You can install the package via Composer by running the following command:

``` bash
$ composer require tabuna/failback
```

## Usage

#### Default Value:

You can use the following code to create an action with a default value:

```php
use Tabuna\FailBack\Action;

// $result = 'default';
$result = Action::make(function () {
    throw new \Exception();
}, 'default')->run();

// or using the short helper
$result = failBack(function () {
    throw new \Exception();
}, 'default')();
```

#### Fallback Features:

You can also define multiple fallback actions using the `fail` method:

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

#### Using Classes:

To define a fallback using a class, you can create an anonymous class with an `__invoke` method:

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

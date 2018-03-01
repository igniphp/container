# ![Igni logo](https://github.com/igniphp/common/blob/master/logo/full.svg) ![Build Status](https://travis-ci.org/igniphp/container.svg?branch=master)

## Igni Container
Licensed under MIT License.

**Igni** container is a `psr-container` compliant lightweight service locator pattern implementation.

## List of features:
- **Simple usage** if you are familiar with `psr-container` there is no learning-curve required for basic usage
- **Context aware** you can define custom instances of services for given use-cases.
- **Auto wiring** required dependencies are auto-injected into your service

## Installation

```
composer install igniphp/container
```

### Basic usage

```php
<?php
$serviceLocator = new Igni\Container\ServiceLocator();
$serviceLocator->set('my_awesome_service', new stdClass());

$myService = $serviceLocator->get('my_awesome_service');

var_dump($myService === $serviceLocator->get('my_awesome_service')); // returns true
```

### Registering shared service

Shared services are services which are instantiated only once and reference is kept in the
registry that way every time service is requested from the container it will return the same
instance.

```php
<?php
use Igni\Container\ServiceLocator;

class Service 
{
    public $a;
    
    public function __construct(int $a = 1) 
    {
        $this->a = $a;    
    }
}

$serviceLocator = new ServiceLocator();
$serviceLocator->share(Service::class, function() { return new Service(2); });

var_dump($serviceLocator->get(Service::class)->a === 2); //true
var_dump($serviceLocator->get(Service::class) === $serviceLocator->get(Service::class)); // true
```

### Factored services

Factored services are instantiated every time container is asked for the service.

```php
<?php
use Igni\Container\ServiceLocator;

class Service 
{
    public $a;
    
    public function __construct(int $a = 1) 
    {
        $this->a = $a;    
    }
}

$serviceLocator = new ServiceLocator();
$serviceLocator->factory(Service::class, function() { return new Service(2); });

var_dump($serviceLocator->get(Service::class)->a === 2); //true
var_dump($serviceLocator->get(Service::class) === $serviceLocator->get(Service::class)); // false
```

### Auto-wiring
Auto-wiring allows you to simply pass fully qualified class name and all type-hinted arguments for that class 
will be resolved automatically by the container.


```php
<?php
use Igni\Container\ServiceLocator;

class A
{
    
}

class Service 
{
    public $a;
    public $number;
    
    public function __construct(int $number = 7, A $a) 
    {
        $this->number = $number;
        $this->a = $a;    
    }
}

$serviceLocator = new ServiceLocator();
$serviceLocator->share(A::class);
$serviceLocator->share(Service::class);

var_dump($serviceLocator->get(Service::class)->a instanceof A);// true
```

That's all folks!

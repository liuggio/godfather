# Godfather

| ![godfather](http://images.wikia.com/cybernations/images/archive/c/c9/20071008043557!Godfather_hand_black.png) | A library for the strategy pattern in PHP, if you use Symfony2 you could easily integrate Godfather with the bundle.   |
| ------- |-----|

 1. [The Strategy pattern](#the-strategy-pattern)
 2. [Installation](#how-it-works)
 3. [Symfony2 bundle](#using-the-symfony2-bundle)
 4. [Contribution](#contribution)

[![travis-ci](https://secure.travis-ci.org/PUGX/godfather.png)](http://travis-ci.org/PUGX/godfather) [![Latest Stable Version](https://poser.pugx.org/PUGX/godfather/v/stable.png)](https://packagist.org/packages/PUGX/godfather) [![Total Downloads](https://poser.pugx.org/PUGX/godfather/downloads.png)](https://packagist.org/packages/PUGX/godfather) [![Latest Unstable Version](https://poser.pugx.org/PUGX/godfather/v/unstable.png)](https://packagist.org/packages/PUGX/godfather)

------------------------------------------------------

## The Strategy Pattern

http://en.wikipedia.org/wiki/Strategy_pattern

### Intent

Define a family of algorithms, encapsulate each one, and make them interchangeable.
Strategy lets the algorithm vary independently from clients that use it.

## TL;DR

Given an object, you want to know its service.

eg. `Entity\Mug` has a `MugService` and `Entity\Tshirt` has a `TshirtService`

``` php
$product = random(0,1)? new Entity\Mug: new Entity\Product
$productService = $godfather->getStrategy('service', $product);
// also works with
$productService = $godfather->getService($product);
echo get_class($productService);
// will be randomly TshirtService or MugService
```

## Sandbox

A working example is at [example/godfather.php](example/godfather.php)

``` cli
cd example
php godfather.php
```

## When do you need a strategist as Godfather?

- If you have a lot of classes that differs by their behaviour...
- If you have multiple conditional statements in order to define different behaviours...
- Given an object you want to know its manager/service/handler/provider/repository/...

## Installation

`composer require pugx/godfather ~0.1`

## A simple use case

The problem is that you have an object and you want to handle it properly.

## How it works

This library does not try to duplicate the services, or to create a new container,
but uses aliases in order to have a mapping between services and names.

An object is converted by the `Context::getStrategyName` more info at [changing the Context::Converter](#changing-the-contextconverter).

### The smelling code

If your code look like this you will need the godfather's protection :)

```php
// Pseudo Code
class Cart
  function add(ProductInterface $product, OptionsInterface $options)
  {
    if ($product instanceOf Mug) {
        $item = $mugManager->add($options);
    }
    if ($product instanceOf Tshirt) {
        $item = $tshirtManager->add($options);
    }
    // ...
 }
```
### The strategist

```php
// Pseudo Code
class Cart
  function add(ProductInterface $product, OptionsInterface $options)
  {
    $item = $this->godfather->getManager($product)->add($options);
    // ...
 }
```

### GodFather and an array as DIC

```php
$container =  new Container\ArrayContainerBuilder();
$container->set('mug_service', new Your\MugService);
$container->set('tshirt_service', new Your\TshirtService);

$godfather = new Godfather($container, 'godfather');

$godfather->addStrategy('service', 'Mug', 'mug_service');
$godfather->addStrategy('service', 'Tshirt', 'tshirt_service');


// Step2. usage
class Cart
  public function __construct($godfather)
  //...
  public function add(ProductInterface $product, OptionsInterface $options)
  {
    // get the strategy for cart with the context $product
    $service = $this->godfather->getStrategy('service', $product);
    // or $strategy = $this->godfather->getCart($product);

    return $strategy->addToCart($product, $options);
 }
```

### GodFather and Symfony Dependency Injection Container

```php
$container =  new Container\ArrayContainerBuilder();
$loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
$loader->load('services.xml');

$godfather = new Godfather($container, 'godfather');

// Step2. usage
class Cart
  public function __construct($godfather)
  //...
  public function add(ProductInterface $product, OptionsInterface $options)
  {
    // get the strategy for cart with the context $product
    $service = $this->godfather->getStrategy('service', $product);
    // or $strategy = $this->godfather->getService($product);
```

## Using the Symfony2 Bundle

### Install the Bundle

Add the bundle in the `app/AppKernel.php`
```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new PUGX\GodfatherBundle\GodfatherBundle(),
```
### Configuring `app/config/config.yml`

#### Minimal Configuration

```yml
# add the below configuration only if you need to specify the fallback or the interface.
godfather:
    default:
        contexts:
            manager: ~ # your strategy name
```

#### With the fallback strategy

```yml
# add the below configuration only if you need to specify the fallback or the interface.
godfather:
    default:
        contexts:
            manager:
                fallback: manager_standard  # need a reference to a defined service
```

### Set your strategies:

```yml
services:
    manager_standard:
        class: StandardProductManager

    manager_mug:
        class: MugManager
        tags:
            -  { name: godfather.strategy, context_name: 'manager', context_key: Mug }

    manager_tshirt:
        class: TshirtManager
        tags:
            -  { name: godfather.strategy, context_name: 'manager', context_key: Tshirt }
```

### Using in the controller:

```php
$product = new \Product\ShoeProduct();
$manager = $container->get('godfather')->getManager($product);
// or $manager = $container->get('godfather')->getStrategy('manager', $product);

// then $manager->doSomethingGreat();
```

### Advanced with multiple instances

Instead of default you could configure your strategy in different godfather instances.

```yml
godfather:
    death:
        contexts:
            manager: ~
    life:
        contexts:
            manager: ~
```

the strategies:

```yml
services:
    manager.entity_life:
        class: EntityProductManager
        arguments:    ['life']
        tags:
        -  { name: godfather.strategy, instance:'life', context_name: 'manager', context_key: %product.show.class% }

    manager.entity_death:
        class: EntityProductManager
        arguments:    ['death']
        tags:
        -  { name: godfather.strategy, instance:'death', context_name: 'manager', context_key: %product.show.class% }
```
and then the code with multiple instances

``` php
$this->getContainer('godfather.life')->getManager($entity);
$this->getContainer('godfather.death')->getManager($entity);
```

### Changing the Context::Converter

The `Godfather\Context\Context::getStrategyName` transforms an object into a strategy name,
the default one just extract from the $object the short class name.

If you want another converter create your class extends the ContextInterface and then:


```yml
godfather:
    deafault:
        contexts:
            manager:
                class: \My\Context
```

## Contribution

Active contribution and patches are very welcome.
To keep things in shape we have quite a bunch of unit tests. If you're submitting pull requests please
make sure that they are still passing and if you add functionality please
take a look at the coverage as well it should be pretty high :)

```bash
composer create-project pugx/godfather --dev -s dev
cd godfather
bin/phpunit
```

## License

[The license is visible here](LICENSE).
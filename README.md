GodFather of the Strategy Pattern
==========================================

Be careful this is not stable and is not production-ready.

## Strategy Pattern

http://en.wikipedia.org/wiki/Strategy_pattern

## Intent

Define a family of algorithms, encapsulate each one, and make them interchangeable.
Strategy lets the algorithm vary independently from clients that use it.

## When do you need a Godfather?

- If you have a lot of classes that differs only by their behaviour...
- If you have multiple conditional statements in order to define different behaviours...
- Given an object you want to know its manager...

## Installation

`composer require liuggio/godfather dev-master`

## A simple use case

Imagine that you want to add a product into a cartitem with some options.
The problem is that you have multiple products, and each product has a different policy/behaviour.

You could find the code of this use case in: [tests/Godfather/Test/FunctionalTest.php](https://github.com/liuggio/godfather/blob/master/tests/Godfather/Test/FunctionalTest.php)                                                                                                                                                                                                                      tests/GodFather/Test/FunctionalTest.php

**before the cure:**

```php
// Pseudo Code
class Cart
  function add(ProductInterface $product, OptionsInterface $options)
  {
    if ($product instanceOf Bus) {
        set $cartItem->inbound = true;
    }
    if ($product instanceOf Tshirt) {
        // set ...
    }
    return $cartItem;
 }
```

**With GodFather:**

```php
// Step1. init
$godfather = new Godfather();
//                   |-context name---Interface to respect (optional)----Fallback Strategy-(optional)-|
$godfather->addContext('cart', 'Godfather\Test\Fixture\CartItemInterface', new StandardCartItem());
// start adding billion of strategies
//                   |-context name---------------context key----------------Strategy-------|
$godfather->addStrategy('cart', 'Godfather\Test\Fixture\Entity\Ticket', new TicketCartItem());
$godfather->addStrategy('cart', 'Godfather\Test\Fixture\Entity\Socket', new SocketCartItem());

// Step2. usage
class Cart
  public function __construct($godfather)
  //...
  public function add(ProductInterface $product, OptionsInterface $options)
  {
    // get the strategy for cart with the context $product
    $strategy = $this->godfather->getStrategy('cart', $product);
    // or $strategy = $this->godfather->getCart($product);

    return $strategy->addToCart($product, $options);
 }
```

## Another use case, the manager

You want to call the correct manager, starting from the entity:
```php
$godfather = new Godfather();
// the context is created if is not found.
$godfather->addStrategy('manager', 'Product\ShoeProduct', new ShoeProductManager());
$godfather->addStrategy('manager', 'Product\PillowProduct', new PillowProductManager());

$manager = $this->godfather->getManager($product);
// or $manager = $this->godfather->getStrategy('manager', $product);
```
## Using the Symfony2 Bundle

Add the bundle in the `app/AppKernel.php`
```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Godfather\GodfatherBundle\GodfatherBundle(),
```
Modify the `app/config/config.yml` only if you need:
```yml
// add the below configuration only if you need to specify the fallback or the interface.
godfather:
    contexts:
        manager:
            fallback: %manager.standard.class%
            interface: %manager.interface.class%
        cart: ~
```

Set your strategies:
```yml
services:
    manager.shoe:
        class: ShoeProductManager
        tags:
            -  { name: godfather.strategy, context_name: 'manager', context_key: %product.show.class% }

    manager.pillow:
        class: PillowProductManager
        tags:
            -  { name: godfather.strategy, context_name: 'manager', context_key: %product.pillow.class% }

    payment.pillow:
        class: PillowPaymentManager
        tags:
            -  { name: godfather.strategy, context_name: 'payment', context_key: %payment.pillow.class% }
```

then use it in the controller:
```php
$product = new /Product/ShoeProduct();
$manager = $container->get('godfather')->getManager($product);
// or $manager = $container->get('godfather')->getStrategy('manager', $product);
$manager->...
```

## Contribution

Active contribution and patches are very welcome.
To keep things in shape we have quite a bunch of unit tests. If you're submitting pull requests please
make sure that they are still passing and if you add functionality please
take a look at the coverage as well it should be pretty high :)

```bash
composer create-project liuggio/godfather --dev -s dev
cd godfather
bin/phpunit
```

## TODO

please help me.

1. improve Context Factory
2. gather feedback
3. annotation

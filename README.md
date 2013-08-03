GodFather of the Strategy Pattern
==========================================

## Strategy Pattern

http://en.wikipedia.org/wiki/Strategy_pattern

## Intent

Define a family of algorithms, encapsulate each one, and make them interchangeable.
Strategy lets the algorithm vary independently from clients that use it.

## When do you need a Godfather?

- If you have a lot of classes that differs only by their behaviour...
- If you have multiple conditional statements in order to define different behaviours...
- Given an Object you want to know its manager...

## Installation

`composer require liuggio/godfather dev-master``

## A simple use case

Imagine that you want to add to a cart item a product and some options.
The problem arises when the products have different policies/behaviours.

You could find the code of this use case in: [tests/Godfather/Test/FunctionalTest.php](https://github.com/liuggio/godfather/blob/master/tests/Godfather/Test/FunctionalTest.php)                                                                                                                                                                                                                      tests/GodFather/Test/FunctionalTest.php

before the cure:

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

**With your GodFather**

```php
// Step1. init
$godfather = new Godfather();
//                   |-context name---Interface to respect---------------Fallback Strategy-------|
$godfather->addContext('cart', 'Godfather\Test\Fixture\CartItemInterface', new StandardCartItem());

// start adding billion of strategy
//                   |-context name---------------context--------------------Strategy-------|
$godfather->addStrategy('cart', 'Godfather\Test\Fixture\Entity\Ticket', new TicketCartItem());
$godfather->addStrategy('cart', 'Godfather\Test\Fixture\Entity\Socket', new SocketCartItem());

// Step2. usage
class Cart
  public function __construct($godfather)
  //...
  public function add(ProductInterface $product, OptionsInterface $options)
  {
    // get the strategy for cart with the context $product
    $strategy = $this->godfather->getStrategy('cart', $product)
    return $strategy->addToCart($product, $options);
 }
```

## Another use case, the manager

You want to call the correct manager, starting from the entity:

```php

$godfather = new Godfather();
// start adding billion of strategy
// the context is created if is not found
$godfather->addStrategy('manager', 'Product/ShoeProduct', new ShoeProductManager());
$godfather->addStrategy('manager', 'Product/PillowProduct', new PillowProductManager());

$manager = $this->godfather->getStrategy('manager', $product);
```
## Using the Symfony2 Bundle

add a context only if you need to specify the fallback or the interface, otherwise the context specification is not needed.

```yml
// config.yml

godfather:
    contexts:
        manager:
            fallback: %manager.standard.class%
            interface: %manager.interface.class%
        cart: ~
```

Set in your Application the strategy:

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
$manager = $container->get('godfather')->getStrategy('manager', $product);
$manager->...
```

## Contribution

   Active contribution and patches are very welcome.
   To keep things in shape we have quite a bunch of unit tests. If you're submitting pull requests please
   make sure that they are still passing and if you add functionality please
   take a look at the coverage as well it should be pretty high :)

   - First fork or clone the repository

   ```
   git clone git://github.com/liuggio/godfather.git
   cd godfather
   composer.phar install
   bin/phpunit
   ```

## TODO

please help me.

1. create sf2Bundle
2. improve factory
3. gather feedback

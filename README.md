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

## Installation

`composer require liuggio/godfather dev-master``

## A simple use case

Imagine that you want to add to a cart item a product and some options.
The problem arises when the products have different policies/behaviours.

You could find the code of this use case in:                                                                                                                                                                                                                           tests/GodFather/Test/FunctionalTest.php

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

  function add(ProductInterface $product, OptionsInterface $options)
  {
    // get the strategy for cart with the context $product
    $strategy = $this->godfather->getStrategy('cart', $product)
    return $strategy->addToCart($product, $options);
 }
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

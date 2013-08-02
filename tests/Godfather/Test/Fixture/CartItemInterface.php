<?php

namespace Godfather\Test\Fixture;

use Godfather\Test\Fixture\Entity\Product;

Interface CartItemInterface
{
    public function addToCart(Product $product, $string);
}

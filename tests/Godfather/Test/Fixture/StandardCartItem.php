<?php

namespace Godfather\Test\Fixture;

use Godfather\Test\Fixture\Entity\Product;

Class StandardCartItem implements CartItemInterface
{
    public function addToCart(Product $product, $string)
    {
        return 'standard.' . $string;
    }
}
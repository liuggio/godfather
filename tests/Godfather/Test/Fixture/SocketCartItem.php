<?php

namespace Godfather\Test\Fixture;

use Godfather\Test\Fixture\Entity\Product;

Class SocketCartItem implements CartItemInterface
{
    public function addToCart(Product $product, $string)
    {
        return 'socket.' . $string;
    }
}
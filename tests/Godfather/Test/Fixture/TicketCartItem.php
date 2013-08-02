<?php

namespace Godfather\Test\Fixture;

use Godfather\Test\Fixture\Entity\Product;

Class TicketCartItem implements CartItemInterface
{
    public function addToCart(Product $product, $string)
    {
        return 'ticket.' . $string;
    }
}
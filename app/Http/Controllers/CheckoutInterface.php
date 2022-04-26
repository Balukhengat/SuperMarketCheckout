<?php

namespace App\Http\Controllers;

interface CheckoutInterface
{
    /**
     * Adds an item to the checkout
     *
     * @param $item int
     * @param $quantity int
     */
    public function scanItems(int $item, int $quantity);

    /**
     * Calculates the total price of all items in this checkout
     *
     * @return int
     */
    public function checkout(): int;
}
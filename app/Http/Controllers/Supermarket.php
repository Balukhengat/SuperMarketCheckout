<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\SpecialPrice;

class Supermarket implements CheckoutInterface
{
    private $totalItems           = array();
    private $totalDiscount        = 0;
    private $priceWithoutDiscount = 0;
    private $finalPrice           = 0;

    /**
     * Adds an item to the checkout
     *
     * @param $item int
     * @param $quantity int
     */
    public function scanItems($item, $quantity) {
        $this->totalItems[$item] = $quantity;
    }

    /**
     * Calculates the total price of all items in this checkout
     *
     * @return int
     */
    public function checkout() : int {

        $products = Products::with('SpecialPrice')->whereIn('item',array_keys($this->totalItems))->get()->toArray();

        if(empty($products)) {
            return 0;
        }

        foreach ($products as $key => $items) {
            $item                       = $items['item'];
            $totalPriceWithoutDiscount  = $items['unit_price'] * $this->totalItems[$item];
            $this->totalDiscount        = 0;
            $this->priceWithoutDiscount = 0;

            if($items["special_price"]) {
                foreach($items['special_price'] as $discount) {
                    if($discount['quantity']) {
                        // Code to calculate individual item price
                        if($this->totalItems[$item] >= $discount['quantity']) {
                            $this->calculateDiscount($discount['quantity'], $items, $discount, false);
                        } else {
                            $this->finalPrice += $totalPriceWithoutDiscount;
                        }
                    } else {
                        // Code to calculate total price having 'purchased with' relation.
                        $discountWithQty = array_key_exists($discount['purchased_with'], $this->totalItems) ? $this->totalItems[$discount['purchased_with']] : 0;
                        if($this->totalItems[$item] >= $discountWithQty && $discountWithQty !=0 ) {
                            $this->calculateDiscount($discountWithQty, $items, $discount, true);
                        } else {
                             $this->finalPrice += $totalPriceWithoutDiscount;
                        }
                    }
                }
            } else {
                $this->finalPrice += $totalPriceWithoutDiscount;
            }
        }

        return $this->finalPrice;
    }


    /**
     * Calculates the total discount for all items
     *
     * @param $discountQty   int
     * @param $items         int
     * @param $discount      array
     * @param $purchasedWith bool
     * 
     * @return void
     */
    private function calculateDiscount($discountQty, $items, $discount, $purchasedWith=false) : void {
        if(!$purchasedWith) {
            $numberOfSets            = floor($this->totalItems[$items['item']] / $discount['quantity']);
            $this->totalDiscount     += ($discount['price'] * $numberOfSets );
            $quantityWithoutDiscount = $this->totalItems[$items['item']] % $discountQty;
        } else {
            $this->totalDiscount     += ($discount['price'] * $discountQty );
            $quantityWithoutDiscount = $this->totalItems[$items['item']] - $discountQty;
        }

        $this->priceWithoutDiscount = $quantityWithoutDiscount * $items['unit_price'] ;
        $this->finalPrice           += ($this->priceWithoutDiscount + $this->totalDiscount);
    }
}

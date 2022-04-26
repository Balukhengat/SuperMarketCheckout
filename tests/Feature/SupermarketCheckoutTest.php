<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\Supermarket;

class SupermarketCheckoutTest extends TestCase
{
    
    /**
     * test cases for item which does not match with our productss
     *
     * @return void
     */
    public function test_scan_items_does_not_match()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('P',10);

        $this->assertEquals(0, $supermarket->checkout());
    }

    /**
     * test cases for A item
     * Special offer is 3 for 130
     * unit price is 50
     *
     * @return void
     */
    public function test_scan_A_items_with_quantity()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('A',10);

        $this->assertEquals(440, $supermarket->checkout());
    }

    /**
     * test cases for B item
     * Special offer is 2 for 45
     * unit price is 30
     *
     * @return void
     */
    public function test_scan_B_items_with_quantity()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('B',5);

        $this->assertEquals(120, $supermarket->checkout());
    }

    /**
     * test cases for D item
     * Special offer is 5 if purchased with an 'A'
     * unit price is 15
     *
     * @return void
     */
    public function test_scan_D_items_with_quantity()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('D',7);

        $this->assertEquals(105, $supermarket->checkout());
    }

    /**
     * test cases for E item
     * No special offer
     * unit price is 5
     *
     * @return void
     */
    public function test_scan_E_items_with_quantity()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('E',3);

        $this->assertEquals(15, $supermarket->checkout());
    }


    // Mixed scenarios
    /**
     * test cases for item A and item B 
     * output: 255
     * @return void
     */
    public function test_scan_A_B_items_with_quantity()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('A',4);
        $supermarket->scanItems('B',3);

        $this->assertEquals(255, $supermarket->checkout());
    }

    /**
     * test cases for item A and item B 
     * output: 255
     * @return void
     */
    public function test_scan_A_D_items_with_quantity()
    {
        $supermarket = new Supermarket();

        $supermarket->scanItems('A',4);
        $supermarket->scanItems('D',6);

        $this->assertEquals(230, $supermarket->checkout());
    }
}

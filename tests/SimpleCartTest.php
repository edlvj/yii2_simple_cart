<?php

namespace yii2_simple_cart\tests;
use Yii;

class SimpleCartTest extends \Codeception\Test\Unit {

	public $card;

	public function _before()
    {
      // error_reporting( E_ERROR );
    	$this->cart = new SimpleCart;
    	$this->cart->addById(1, 2, 3);
    }
	
	public function testAddbyId()
    {
		$this->assertEquals([[1] => [['quantity'=> 2, 'price'=> 3]]], $this->cart->getCart());
    }

    public function testDropById()
    {
		$this->cart->dropById(1, 2);
		$this->assertEquals([], $this->cart->getCart());
    }

    public function testClear() {
    	$this->cart->clear();
    	$this->assertEquals([], $this->cart->getCart());
    }

    public function testGetOrderPrice()
    {
		$this->cart->assertEquals(6, $this->cart->getOrderPrice());
    }

    public function testGetSubTotal()
    {
		$this->cart->assertEquals(6, $this->cart->getSubTotal(1));
    }

    public function testGetQuantity()
    {
		$this->cart->assertEquals(2, $this->cart->getQuantity(1));
    }

    public function testAllQuantity()
    {
		$this->cart->assertEquals(2, $this->cart->getAllQuantity());
    }

    public function testGetCartIds()
    {
		$this->cart->assertContains([1], $this->cart->getCartIds());
    }

    public function testUpdateQuantity()
    {
    	$this->cart->assertContains($this->cart->getQuantity(1), $this->cart->updateQuantity(1, 4));
    }

    public function testdeleteItem()
    {
    	$this->cart->deleteItem(1);
    	$this->cart->assertEquals([], $this->cart->getCart());
    }
}
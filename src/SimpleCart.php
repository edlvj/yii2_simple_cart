<?php
namespace edlvj\src\simplecart;

use Yii;

class SimpleCart extends \yii\base\Component {
    
    protected $cart_name = 'cart';
    protected $cart;

    public function init() {
       $this->cart = $this->getCart();
    }

    public function addById($id, $quantity, $price = null) {
    	
        if ( !is_numeric($quantity) || $quantity < 1) 
	        throw new \yii\base\Exception('Invalid quantity.');
	    
	    if (!empty($price) && !is_numeric($price) || $price < 0) 
	        throw new \yii\base\Exception('Invalid price.');

	    if (isset($this->cart[$id])) 
	        $row = $this->updateQuantity($id, $this->cart[$id]['quantity'] + $quantity);
	    else 
	        $row = $this->insertItem($id, $quantity, $price);
	    return $row;
    }

    public function dropById($id, $quantity = null) {

        if(isset($this->cart[$id])) {

            if($quantity) 
              $this->updateQuantity($id, $this->cart[$id]['quantity'] - $quantity);
            else
              $this->deleteItem($id);  

            return true; 
        }  
    }

    public function clear() {
	    unset(Yii::$app->session[$this->cart_name]);
    }

    public function getOrderPrice() {
        if($this->cart):
            foreach($this->cart as $key => $value):
              $sum[] = $this->cart[$key]['price'] * $this->cart[$key]['quantity'];
            endforeach;
        endif;

        return array_sum($sum);
    }

    public function getSubTotal($id) {   
        if($this->cart) 
           $subTotal = $this->cart[$id]['price'] * $this->cart[$id]['quantity']; 
           return $subTotal;
    }

    public function getQuantity($id) {
        if(isset($this->cart[$id]))
           return $this->cart[$id]['quantity'];
    }

    public function getAllQuantity() {
        $sum = 0; 

        if($this->cart):
            foreach($this->cart as $key => $value):
              $sum += $this->cart[$key]['quantity'];
            endforeach;
        endif;

        return $sum;
    }

	public function getCartIds() {

        if(is_array($this->cart))
          return array_keys($this->cart);
        else
          return [];
    }    

    public function getCart() {
        return Yii::$app->session[$this->cart_name]; 
    }

    public function updateQuantity($id, $quantity) {
        $this->cart[$id]['quantity'] = $quantity;
        return $this->setSession($this->cart);
    }

    public function deleteItem($id) {     
        unset($this->cart[$id]);
        return $this->setSession($this->cart);
    }

    private function setSession($cart_item) {
        Yii::$app->session[$this->cart_name] = $cart_item;
        return true;
    }

    private function insertItem($id, $quantity, $price) {
        $this->cart[$id] = ['quantity' => $quantity, 'price' => $price ]; 
        return $this->setSession($this->cart);
    }
}

<?php
namespace edlvj\simplecart;

use Yii;

class SimpleCart extends \yii\base\Component {
    
    protected $cart_name = 'cart';

    public function addById($id, $quantity, $price = null) {
    	
        if ( !is_numeric($quantity) || $quantity < 1) 
	        throw new \yii\base\Exception('Invalid quantity.');
	    
	    if (!empty($price) && !is_numeric($price) || $price < 0) 
	        throw new \yii\base\Exception('Invalid price.');

	    $cart = $this->getCart();

	    if (isset($cart[$id])) 
	        $row = $this->updateQuantity($id, $cart[$id]['quantity'] + $quantity);
	    else 
	        $row = $this->insertItem($id, $quantity, $price);
	    return $row;
    }

    public function dropById($id, $quantity = null) {

        $cart = $this->getCart();
        
        if(isset($cart[$id])) {

            if($quantity) 
              $this->updateQuantity($id, $cart[$id]['quantity'] - $quantity);
            else
              $this->deleteItem($id);  

            return true; 
        }  
    }

    public function clear() {
	    unset(Yii::$app->session[$this->cart_name]);
    }

    public function getOrderPrice() {
        $cart = $this->getCart();
        
        if($cart):
            foreach($cart as $key => $value):
              $sum[] = $cart[$key]['price'] * $cart[$key]['quantity'];
            endforeach;
        endif;

        return array_sum($sum);
    }

    public function getSubTotal($id) {
        $cart = $this->getCart();        
        if($cart) 
           $subTotal = $cart[$id]['price'] * $cart[$id]['quantity']; 
           return $subTotal;
    }

    public function getCountProduct($id) {
        $cart = $this->getCart();
        
        if(isset($cart[$id]))
           return $cart[$id]['quantity'];
    }

    public function getCountQuantity() {
        $cart = $this->getCart();
        $sum = 0; 

        if($cart):
            foreach($cart as $key => $value):
              $sum += $cart[$key]['quantity'];
            endforeach;
        endif;

        return $sum;
    }

	public function getCartIds() {
        $cart = $this->getCart();
       
        if(is_array($cart))
          return array_keys($cart);
        else
          return [];
    }    

    public function getCart() {
        return Yii::$app->session[$this->cart_name]; 
    }

    private function setSession($cart_item) {
        Yii::$app->session[$this->cart_name] = $cart_item;
        return true;
    }

    private function insertItem($id, $quantity, $price) {
        $cart = $this->getCart();
        $cart[$id] = ['quantity' => $quantity, 'price' => $price ]; 
        return $this->setSession($cart);
    }

    public function updateQuantity($id, $quantity) {

        $cart = $this->getCart(); 
        $cart[$id]['quantity'] = $quantity;
        return $this->setSession($cart);
    }

    public function deleteItem($id) {     
        $cart = $this->getCart(); 
        unset($cart[$id]);
        return $this->setSession($cart);
    }
}
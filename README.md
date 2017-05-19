Simple Cart for Yii 2
=======================

Installation with composer.
```
php composer.phar require --prefer-dist edlvj/yii2_simple_cart "*"
```
or 
```
"edlvj/yii2_simple_cart": "*"
```

Configuration

```php

return [
    //....
    'components' => [
        'cart' => [
            'class' => 'yii2_simple_cart\src\SimpleCart',
        ],
    ]
];

```

Using the simple shopping cart
```php

$card = Yii::$app->card;

// Add item  to card
$card->addById($id, $quantity, $price = null);

// Delete item
$card->dropById($id, $quantity = null);

// Update Cart Item
$card->updateQuantity($id, $quantity);

// Get Order Total price
$card->getOrderPrice();

// Get all Quantity
$card->getAllQuantity();

//Clear cart
$card->clear();

//Showing Elements in CartPage
Products::find($cart->getCartIds());

```
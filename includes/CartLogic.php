<?php

ini_set('error_reporting', E_ALL);

ini_set('display_errors', 'On');

class CartLogic {

const STANDARD_SHIPPING = 25;
const EXPRESS_SHIPPING = 50;

private $shippingTotal = 0;
private $cartSubtotal = 0;
private $cartQty = 0;


public function displayCart() {

$dataAccess = DBHelper::setConnectionInfo();




if (isset($_SESSION['cart'])) {

$painting = new PaintingDB($dataAccess);

$artist = new ArtistDB($dataAccess);

$frame = new FrameDB($dataAccess);

$glass = new GlassDB($dataAccess);

$matt = new MattDB($dataAccess);

if ( isset($_GET['update'])) { //Does the cart need to be updated?
$this->updateCart();
}

$frames = $frame->getCartInfo();

$mattTypes = $matt->getCartInfo();
foreach ($mattTypes as &$matt) {
if ($matt['Title'] != '[None]'){
$matt['Price'] = 10; 
} else {
$matt['Price'] = 0;
}

}

$glassTypes = $glass->getCartInfo();


foreach ($_SESSION['cart'] as $item) {

if (isset ($item['id'])) {

$info = $painting->findById($item['id']); //Info about painting

if ($item['qty'] > 0) {


//if (!isset($_GET['frame'], $_GET['glass'], $_GET['matt'])) {
$chosenFrame = array_search($item['frame'], array_column($frames, 'Title'));
$chosenGlass = array_search($item['glass'], array_column($glassTypes, 'Title'));
$chosenMatt = array_search($item['matt'], array_column($mattTypes, 'Title'));

//}
//else {
//$chosenFrame = array_search($_GET['frame'], array_column($frames, 'Title'));
//$chosenGlass = array_search($_GET['glass'], array_column($frames, 'Title'));
//$chosenMatt = array_search($_GET['matt'], array_column($frames, 'Title'));
//}

$price = 0;

$price += $frames[$chosenFrame]["Price"] + $glassTypes[$chosenGlass]["Price"] + $mattTypes[$chosenMatt]["Price"];
$price += $info[0]['MSRP']*$item['qty'];
$this->cartSubtotal += $price;
$this->cartQty++;


    echo '<form class="ui form" action="cart.php"><tr>

      <td><img src="images/art/works/square-medium/' . $item["image"] . '.jpg">

	  </td>

	  <td>

	  <h2>' . $info[0]["Title"] . '</h2>

	  <h3>' . $artist->getArtistName($info[0]['ArtistID']) . '</h3>

	  </td>

      <td>

	                          <div class="three fields">

                            <div class="five wide field">

                                <label>Frame</label>

                                <select id="frame" name="frame[]" class="ui search dropdown">';

								echo "<option>" . $frames[$chosenFrame]["Title"] . " - " . money_format('$%i',$frames[$chosenFrame]["Price"]) . "</option>";
						  						foreach ($frames as $names) {																
												
												if ($names["Title"] != $frames[$chosenFrame]["Title"]) {

						  echo "<option>" . $names["Title"] . " - " . money_format('$%i',$names["Price"]) . "</option>";
												}																					

						  }





	echo '</select>

                            </div>

                            <div class="five wide field">

                                <label>Glass</label>

                                <select id="glass" name="glass[]" class="ui search dropdown">';

								echo "<option>" . $glassTypes[$chosenGlass]["Title"] . " - " . money_format('$%i',$glassTypes[$chosenGlass]["Price"]) . "</option>";
															foreach ($glassTypes as $glassNames) {
if ($glassNames["Title"] != $glassTypes[$chosenFrame]["Title"]) {
						  echo '<option>' . $glassNames['Title'] . ' - ' . money_format('$%i', $glassNames['Price']) . '</option> ';//$glassNames['Price'];
}
						  }

			echo

                                '</select>

                            </div>

                            <div class="five wide field">

                                <label>Matt</label>

                                <select id="matt" name="matt[]" class="ui search dropdown">';
								echo "<option>" . $mattTypes[$chosenMatt]["Title"] . " - " . money_format('$%i',$mattTypes[$chosenMatt]["Price"]) . "</option>";
															foreach ($mattTypes as $mattNames) {
if ($mattNames["Title"] != $mattTypes[$chosenFrame]["Title"]) {
						  echo '<option>' . $mattNames['Title'] . ' - ' . money_format('$%i', $mattNames['Price']) .'</option>';
}
						  }

echo '</select>

                            </div>

                        </div>

						</div>

	  </td>

	  <td>

	                              <div class="sixteen wide field">

                                <input type="number" name="qty[]" value="' . $item["qty"] . '">

                            </div>


	  </td>

	  <td>

	  ' . money_format('$%i', $price*$item["qty"]) . '

	  </td>

    </tr>';
	
} else {

unset($_SESSION['cart'][$info[0]['PaintingID']]);
}
	}

	}

}

else {

$this->emptyCart();

}



}



public function emptyCart() {

echo '<div class="ui one column stackable center aligned page grid">

<div class="ui error message">

  <i class="close icon"></i>

  <div class="header">

    Your shopping cart is empty!

  </div>

  <ul class="list">

    <li>Feel free to browse our inventory.</li>

    <li>If you have any questions or concerns please email us at info@info.com.</li>

  </ul>

</div>';



}



public function updateCart() {
$newFrame = $_GET['frame'];
$newGlass = $_GET['glass'];
$newMatt = $_GET['matt'];
$newQty = $_GET['qty'];
$i = 0;

foreach ($_SESSION['cart'] as &$item) {

if (isset ($item['image'])) {

$item['frame'] = strtok($newFrame[$i], " ");
$item['glass'] = strtok($newGlass[$i], " ");
$item['matt'] = strtok($newMatt[$i], " ");

//echo print_r($item['matt']);

if (isset ($_GET['qty'])) {
$item['qty'] = strtok($newQty[$i], " ");
}
$i++;

}
}
}

public function getCartSubTotal() {
return money_format('$%i', $this->cartSubtotal);
}

public function getShippingOption() { 

}

public function shippingTotal() {


if(isset($_GET['shipping'])) {

if ($this->cartSubtotal < 1500 && $_GET['shipping'] == 'standard')
{
	$this->shippingTotal = self::STANDARD_SHIPPING;
	return money_format('$%i', $this->shippingTotal);
	
} else if ($this->cartSubtotal < 2500 && $_GET['shipping'] == 'standard' || $this->cartSubtotal >= 2500) {

	return money_format('$%i', 0);
	
} else {
	$this->shippingTotal = self::EXPRESS_SHIPPING;
	return money_format('$%i', $this->shippingTotal);
}

} else {

return money_format('$%i', 0);



}


}

function getCartTotal() { 
return money_format('$%i', $this->cartSubtotal + $this->shippingTotal);

}

}
?>

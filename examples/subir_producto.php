<?php
session_start();

require '../Meli/meli.php';
require '../configApp.php';

$meli = new Meli('5957759582547304', 'm6v9Op5FS1YAD9j6C7Z7MAksfXzdK7jc');

if($_GET['code']) {

	// If the code was in get parameter we authorize
	$user = $meli->authorize($_GET['code'], 'https://mlmossmx.herokuapp.com/examples/example_list_item.php');

	// Now we create the sessions with the authenticated user
	$_SESSION['access_token'] = $user['body']->access_token;
	$_SESSION['expires_in'] = $user['body']->expires_in;
	$_SESSION['refresh_token'] = $user['body']->refresh_token;

	// We can check if the access token in invalid checking the time
	if($_SESSION['expires_in'] + time() + 1 < time()) {
		try {
			print_r($meli->refreshAccessToken());
		} catch (Exception $e) {
			echo "Exception: ",  $e->getMessage(), "\n";
		}
	}

	// We construct the item to POST
	$item = array(
		"title" => "Prueba de producto! --kc:off",
        "category_id" => "MLM1055",
        "price" => 10,
        "currency_id" => "MXN",
        "available_quantity" => 1,
        "buying_mode" => "buy_it_now",
        "listing_type_id" => "bronze",
        "condition" => "new",
        "description" => array ("plain_text" => "producto de prueba"),
        "video_id" => "RXWn6kftTHY",
        "warranty" => "12 month",
        "pictures" => array(
            array(
                "source" => "https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/IPhone_7_Plus_Jet_Black.svg/440px-IPhone_7_Plus_Jet_Black.svg.png"
            ),
            array(
                "source" => "https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/IPhone7.jpg/440px-IPhone7.jpg"
            )
        ),
        "attributes" => array(
            array(
                "id" => "EAN",
                "value_name" => "190198043566"
            ),
            array(
                "id" => "COLOR",
                "value_id" => "52019"
            ),
            array(
                "id" => "INTERNAL_MEMORY",
                "value_name" => "16 GB"
            ),
           
            array(
                "id" => "WITH_TOUCH_SCREEN",
                "value_id" => "242085"
                    
            ),
            array(
                "id" => "SIM_SIZES",
                "value_id" => "80452"
            
          
            ),
            array(
                "id" => "BATTERY_CAPACITY",
                "value_name" => "3980 mAh"
            ),
            array(
                "id" => "FRONT_CAMERA_RESOLUTION",
                "value_name" => "7 mpx"
            )
        )
    );
	
	// We call the post request to list a item
	echo '<pre>';
	print_r($meli->post('/items', $item, array('access_token' => $_SESSION['access_token'])));
	echo '</pre>';

} else {

	echo '<a href="' . $meli->getAuthUrl('https://mlmossmx.herokuapp.com/examples/example_list_item.php', Meli::$AUTH_URL['MLM']) . '">Login using MercadoLibre oAuth 2.0</a>';
}

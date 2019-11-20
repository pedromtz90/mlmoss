<?php
session_start();
require 'Meli/meli.php';
require 'configApp.php';

$domain = $_SERVER['HTTP_HOST'];
$appName = explode('.', $domain)[0];
?>

    <!DOCTYPE html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Mercado Libre's API.">
        <meta name="keywords" content="API, PHP, Mercado Libre, SDK, meli, integration, e-commerce">
        <title>Mercado Libre PHP SDK</title>
        <link rel="stylesheet" href="/getting-started/style.css" />
        <script src="script.js"></script>
    </head>

    <body>
        <header class="navbar">
           
            <nav>
                <ul class="nav navbar-nav navbar-right">
                    
                </ul>
            </nav>
        </header>

        <div class="header">
            <div>
                <h1MOSS</h1>
                <h2>MOSS MERCADO LIBRE</h2>
            </div>
        </div>

        <main class="container">
           
            

            <div class="row">
                
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <h3>oAuth</h3>
                    <p>Primero nos autenificamos</p>

                    <?php
                   $meli = new Meli('5957759582547304', 'm6v9Op5FS1YAD9j6C7Z7MAksfXzdK7jc');

                    if($_GET['code'] || $_SESSION['access_token']) {

                        // If code exist and session is empty
                        if($_GET['code'] && !($_SESSION['access_token'])) {
                            // If the code was in get parameter we authorize
                            $user = $meli->authorize($_GET['code'], 'https://mlmossmx.herokuapp.com');

                            // Now we create the sessions with the authenticated user
                            $_SESSION['access_token'] = $user['body']->access_token;
                            $_SESSION['expires_in'] = time() + $user['body']->expires_in;
                            $_SESSION['refresh_token'] = $user['body']->refresh_token;
                        } else {
                            // We can check if the access token in invalid checking the time
                            if($_SESSION['expires_in'] < time()) {
                                try {
                                    // Make the refresh proccess
                                    $refresh = $meli->refreshAccessToken();

                                    // Now we create the sessions with the new parameters
                                    $_SESSION['access_token'] = $refresh['body']->access_token;
                                    $_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
                                    $_SESSION['refresh_token'] = $refresh['body']->refresh_token;
                                } catch (Exception $e) {
                                    echo "Exception: ",  $e->getMessage(), "\n";
                                }
                            }
                        }

                        echo '<pre>';
                            print_r($_SESSION);
                        echo '</pre>';

                    } else {
                        echo '<p><a alt="Login using MercadoLibre oAuth 2.0" class="btn" href="' . $meli->getAuthUrl('https://mlmossmx.herokuapp.com', Meli::$AUTH_URL[$siteId]) . '">Authenticate</a></p>';
                    }
                    ?>

                </div>
                
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h3>Publicar Producto </h3>
                    <p>
                       
                       <br /> <b>Necesitamos primero tener el token</b>
                       
                    </p>
                    <pre class="pre-item">
"title" => "Prueba de producto! NO OFERTAR PRODUCTO DE PRUEBA",
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
                "value_name" => "32 GB"
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
	
                    </pre>

                    <?php
                  $meli = new Meli('5957759582547304', 'm6v9Op5FS1YAD9j6C7Z7MAksfXzdK7jc');

                    if($_GET['code'] && $_GET['publish_item']) {

                        // If the code was in get parameter we authorize
                        $user = $meli->authorize($_GET['code'], 'https://mlmossmx.herokuapp.com');

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
                            "title" => "Prueba de producto! NO OFERTAR ",
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
	
                        
                        $response = $meli->post('/items', $item, array('access_token' => $_SESSION['access_token']));

                        // We call the post request to list a item
                        echo "<h4>Response</h4>";
                        echo '<pre class="pre-item">';
                        print_r ($response);
                        echo '</pre>';

                        echo "<h4>insertado con exito!</h4>";
                        echo "<p>va a ver tu producto</p>";
                        echo '<a target="_blank" href="'.$response["body"]->permalink.'">'.$response["body"]->permalink.'</a><br />';

                    } else if($_GET['code']) {
                        echo '<p><a alt="Publish Item" class="btn" href="../examples/subir_producto.php">Publicar Producto</a></p>';
                    } else {
                        echo '<p><a alt="Publish Item" class="btn disable" href="#">Publicar Producto</a> </p>';
                    }
                    ?>

                </div>

             
            </div>

            <hr>

            <div class="row">
                <h3>Credenciales</h3>
                <div class="row-info col-sm-3 col-md-3">
                    <b>App_Id: </b>
                    <?php echo $appId; ?>
                </div>
                <div class="row-info col-sm-3 col-md-3">
                    <b>Secret_Key: </b>
                    <?php echo $secretKey; ?>
                </div>
                <div class="row-info col-sm-3 col-md-3">
                    <b>Redirect_URI: </b>
                    <?php echo $redirectURI; ?>
                </div>
                <div class="row-info col-sm-3 col-md-3">
                    <b>Site_Id: </b>
                    <?php echo $siteId; ?>
                </div>
            </div>
            <hr>
        </main>
    </body>

    </html>

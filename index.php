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
        <meta name="description" content="Official PHP SDK for Mercado Libre's API.">
        <meta name="keywords" content="API, PHP, Mercado Libre, SDK, meli, integration, e-commerce">
        <title>Mercado Libre PHP SDK</title>
        <link rel="stylesheet" href="/getting-started/style.css" />
        <script src="script.js"></script>
    </head>

    <body>
        <header class="navbar">
            <a class="logo" href="#"><img src="/getting-started/logo-developers.png" alt=""></a>
            <nav>
                <ul class="nav navbar-nav navbar-right">
                    <li><a target="_blank" href="http://developers.mercadolibre.com/getting-started/">Getting Started</a></li>
                    <li><a target="_blank" href="http://developers.mercadolibre.com/api-docs/">API Docs</a></li>
                    <li><a target="_blank" href="http://developers.mercadolibre.com/community/">Community</a></li>
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
                    <p>First authenticate yourself. Authentication is the key to get the most ouf Mercado Libre's API.</p>

                    <?php
                    $meli = new Meli($appId, $secretKey);

                    if($_GET['code'] || $_SESSION['access_token']) {

                        // If code exist and session is empty
                        if($_GET['code'] && !($_SESSION['access_token'])) {
                            // If the code was in get parameter we authorize
                            $user = $meli->authorize($_GET['code'], $redirectURI);

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
                        echo '<p><a alt="Login using MercadoLibre oAuth 2.0" class="btn" href="' . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]) . '">Authenticate</a></p>';
                    }
                    ?>

                </div>
                <div class="col-sm-6 col-md-6">
                    <h3>Get site</h3>
                    <p>Make a simple GET to <a href="https://api.mercadolibre.com/sites">sites resource</a> with your <b>$site_id</b> to obtain information about a a site. Like id, name, currencies, categories, and other settings.</p>
                    <p><a class="btn" href="../examples/example_get.php">GET</a></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h3>Publish an Item</h3>
                    <p>
                        This is a example of how to list an item in <b>MLB</b> (Brasil).
                       <br /> <b>You need to be authenticated to make it work.</b>
                       <br /> To be able to list an item in another country, <a href="https://github.com/mercadolibre/php-sdk/blob/master/examples/example_list_item.php">please update this file</a>, with values according to the site Id where your app works, like <b>category_id</b> and <b>currency</b>.
                     <br />
                    </p>
                    <pre class="pre-item">
"title" => "Prueba de producto! --kc:off",
        "category_id" => "MLB1227",
        "price" => 10,
        "currency_id" => "MX",
        "available_quantity" => 1,
        "buying_mode" => "buy_it_now",
        "listing_type_id" => "bronze",
        "condition" => "new",
        "description" => "Item de Teste. Mercado Livre's PHP SDK.",
        "video_id" => "RXWn6kftTHY",
        "warranty" => "12 month",
        "pictures" => array(
            array(
                "source" => "https://upload.wikimedia.org/wikipedia/commons/f/fd/Ray_Ban_Original_Wayfarer.jpg"
            ),
            array(
                "source" => "https://upload.wikimedia.org/wikipedia/commons/a/ab/Teashades.gif"
            )
        )
    )
                    </pre>

                    <?php
                    $meli = new Meli($appId, $secretKey);

                    if($_GET['code'] && $_GET['publish_item']) {

                        // If the code was in get parameter we authorize
                        $user = $meli->authorize($_GET['code'], $redirectURI);

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
                            "title" => "PRODUCTO DE PRUEBA NO OFERTAR! --kc:off",
        "category_id" => "MLB1227",
        "price" => 10,
        "currency_id" => "MX",
        "available_quantity" => 1,
        "buying_mode" => "buy_it_now",
        "listing_type_id" => "bronze",
        "condition" => "new",
        "description" => "PRODUCTO DE PRUEBA",
        "video_id" => "RXWn6kftTHY",
        "warranty" => "12 month",
        "pictures" => array(
            array(
                "source" => "https://upload.wikimedia.org/wikipedia/commons/f/fd/Ray_Ban_Original_Wayfarer.jpg"
            ),
            array(
                "source" => "https://upload.wikimedia.org/wikipedia/commons/a/ab/Teashades.gif"
            )
        )
    );
                        
                        $response = $meli->post('/items', $item, array('access_token' => $_SESSION['access_token']));

                        // We call the post request to list a item
                        echo "<h4>Response</h4>";
                        echo '<pre class="pre-item">';
                        print_r ($response);
                        echo '</pre>';

                        echo "<h4>Success! Your test item was listed!</h4>";
                        echo "<p>Go to the permalink to see how it's looking in our site.</p>";
                        echo '<a target="_blank" href="'.$response["body"]->permalink.'">'.$response["body"]->permalink.'</a><br />';

                    } else if($_GET['code']) {
                        echo '<p><a alt="Publish Item" class="btn" href="/?code='.$_GET['code'].'&publish_item=ok">Publish Item</a></p>';
                    } else {
                        echo '<p><a alt="Publish Item" class="btn disable" href="#">Publish Item</a> </p>';
                    }
                    ?>

                </div>

             
            </div>

            <hr>

            <div class="row">
                <h3>Your Credentials</h3>
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

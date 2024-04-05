<?php 

define("CLIENT_ID", "AYliHVGBUYvuu3fORQ3MHli5hEmySaPwGb7Wqbp-BC4l2Cc6sikvsdZCuLJtJgsMCFAUFWdNZlzzqvSq");
define("CURRENCY", "MXN");
define("KEY_TOKEN", "ABC.abc-123*");
define("MONEDA", "$");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>
<?php 

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

print_r($_SESSION);
$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad){

        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Electronics Space</title>
    <link rel="icon" href="images/favicon/consola.png" type="image/x-icon">
    <!-- box icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <!-- styles -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- HEADER -->
    <header>
        <!-- NAVEGACION -->
        <div class="nav container">
            <a href="index.php" class="logo"><span>E</span>Space</a>
            <a href="index.php" class="logopng"><img src="images/favicon/consola.png" alt=""></a>
        </div>
    </header>
    
    <!-- SHOP SECTION -->
    <section class="shop container">
        <!-- CART ICON -->
        <a href="checkout.php" class="bx bx-shopping-bag" id="cart-icon" >
            <span id="num_cart" class="badge badge-pill badge-primary"><?php echo $num_cart;?></span>
        </a>
        <h2 class="section-title">Nuestros Productos</h2>

        <!-- CONTENT -->
        <div class="shop-content">
            <div class="container">

                <div class="row">
                    <div class="col-6">
                        <h4>Detalles de pago</h4>
                        <div id="paypal-button-container"></div>
                    </div>
                
                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <?php if ($lista_carrito == null){
                                    echo '<tr><td colspan="5 class="text-center"><b>Lista vacia</b>"></td></tr>';
                                } else {
                                    $total = 0;
                                    foreach($lista_carrito as $producto){
                                        $_id = $producto['id'];
                                        $nombre = $producto['nombre'];
                                        $precio = $producto['precio'];
                                        $descuento = $producto['descuento'];
                                        $cantidad = $producto['cantidad'];
                                        $precio_desc = $precio - (($precio * $descuento) / 100);
                                        $subtotal = $cantidad * $precio_desc;
                                        $total += $subtotal;
                                        ?>
                                <tr>
                                    <td> <?php echo $nombre; ?> </td>
                                    <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($precio_desc,2, '.', ','); ?></div>    
                                    </td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td colspan="3">
                                        <td colspan="2">
                                            <p class="h3" id="total"> Total: <?php echo MONEDA . number_format($total, 2, '.', ','); ?> </p>
                                        </td>
                                    </td>
                                </tr>
                                <?php } ?>            
                            </table>
                        </div>
                    </div>
                </div>
            </div>    
        </div>

    </section>

    <!-- link js -->
    <script src="scripts.js"></script>
    <!-- PayPal Function -->
    <script src="https://www.paypal.com/sdk/js?client-id=AYliHVGBUYvuu3fORQ3MHli5hEmySaPwGb7Wqbp-BC4l2Cc6sikvsdZCuLJtJgsMCFAUFWdNZlzzqvSq&currency=MXN"></script>

</body>
</html>
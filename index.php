<?php 

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// print_r ($$resultado);

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
            <?php foreach ($resultado as $row) { ?>

            <!-- BOX 1 -->
                <div class="product-box">
                    <?php 
                    $id = $row['id'];
                    $imagen = "images/productos/$id/principal.png";
                    if(!file_exists($imagen)) {
                        $imagen = "images/no-imagen.png";
                    }
                    ?>
                    <img src="<?php echo $imagen; ?>" alt="" class="product-img">


                        <span class="product-title"> <?php echo $row['nombre']; ?> </span>
                        <br>    
                        <span class="product-price"> <?php echo MONEDA . number_format($row['precio'], 2, '.', ',' ); ?> </span>
                            <div class="btnDetails">
                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                            </div>
                        <i class="bx bx-shopping-bag add-cart styleaddcartshop" onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN);?>')"></i>
                </div>
            <?php }?>
        </div>

    </section>

    <!-- link js -->
    <script src="scripts.js"></script>
</body>
</html>
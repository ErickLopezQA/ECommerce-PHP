<?php 

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : ''; //Agregamos el isset para verificar que este definido, si no lo esta recibira el valor vacio ''
$token = isset($_GET['token']) ? $_GET['token'] : '';

//Validamos ambos datos, por si se pide la url sin datos, mande el mensaje de error.
if($id == '' || $token == ''){
    echo 'Error al procesar la peticion 2';
    exit();
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN); //Procesamos el token...
    

    if($token == $token_tmp){ //Validamos si el token que el usuario me esta enviando es igual al token que estamos generando.

        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1"); //Averiguamos si el producto con el id que nos indica el usuario existe y si existe, que este activo.
        $sql->execute([$id]); //Hacemos un arreglo, enviandole el id.
        if($sql->fetchColumn() > 0) { //Si fetchColumn es mayor a 0, si encontro un dato y nos arrojara un elemento 

            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]); // Hacemos de nuevo la consulta
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);// Para sacar el descuento.
            $dir_images = 'images/productos/'. $id .'/';

            $rutaImg = $dir_images . 'principal.png';

            if(!file_exists($rutaImg)){
                $rutaImg = 'images/no-imagen.png';
            }

            $imagenes = array();
            if(file_exists($dir_images)){
            $dir = dir($dir_images);

            while(($archivo = $dir->read()) != false) {
                if ($archivo != 'principal.png' && (strpos($archivo, 'png') || strpos($archivo, 'jpeg'))) {
                    $imagenes[] = $dir_images . $archivo;
                }
            }
        }
            $dir->close();
            
    }
    } else { //En caso de que si, solo continuara el proceso.
        echo 'Error al procesar la peticion'; //si no se procesa satisfactoriamente enviamos el mensaje de error.
        exit();
    }
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!-- styles -->
    <link rel="stylesheet" href="style.css">
    <!-- link js -->
    <script src="scripts.js"></script>

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
    <section class="shop container"><br><br>
    <!-- CART ICON -->
    <a href="checkout.php" class="bx bx-shopping-bag" id="cart-icon" >
        <span id="num_cart" class="badge badge-pill badge-primary"><?php echo $num_cart;?></span>
    </a>
        <div class="row">
            <div class="col-md-6 order-md-1">

                <div id="carouselImages" class="carousel slide carousel-fade">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo $rutaImg; ?>" alt="" class="d-block w-100 product-img">
                        </div>
                        <?php foreach($imagenes as $img) { ?>
                            <div class="carousel-item">
                                <img src="<?php echo $img; ?>" alt="" class="d-block w-100 product-img">
                            </div>
                        <?php } ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            </div>
            <div class="col-md-6 order-md-2">
                <h1 class="product-title"> <?php echo $nombre ?> </h1>

                            <?php if($descuento > 0) { ?>
                                <p class="text-danger" ><del> <?php echo MONEDA . number_format($precio, 2, '.', ',' ); ?> </del></p>
                                <h2>
                                    <?php echo MONEDA . number_format($precio_desc, 2, '.', ',' ); ?>
                                    <small class="text-success"> <?php echo $descuento; ?>% descuento. </small>
                                </h2>
                            <?php } else { ?>

                                <h2> <?php echo MONEDA . number_format($precio, 2, '.', ',' ); ?> </h2>

                            <?php }?>


                <p class="product-description lead">
                     <?php echo $descripcion ?> 
                </p>

                    <div class="d-grid gap-3 col-10 mx-auto"><br><br>
                        <button class="btn btn-primary productbtn">Comprar Ahora!</button>
                        <button class="btn btn-outline-primary productbtn add-cart" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')" >Añadir Al Carrito</button>
                    </div>
            </div>
        </div>
       
    </section>
    
</body>
</html>
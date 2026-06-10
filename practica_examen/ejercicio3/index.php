<?php

    try{

        session_start();

        $conn = new PDO('mysql:host=localhost;dbname=tienda_db', 'root', '');

        $select = "SELECT * FROM productos";
        $stmt = $conn->prepare($select);
        $stmt->execute();

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if(isset($_POST['producto_id'])){

            $carrito_select = "SELECT * FROM productos WHERE id = ?";
            $statement = $conn->prepare($carrito_select);
            $statement->execute([$_POST['producto_id']]);

            $carrito = $statement->fetch(PDO::FETCH_ASSOC); 

            
        }

        if(!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = [];
            array_push($_SESSION['carrito'], $carrito);
            $carro_compra = $_SESSION['carrito'];

        }else{
            array_push($_SESSION['carrito'], $carrito);
            $carro_compra = $_SESSION['carrito'];

        }

        if(isset($_POST['vaciar'])){
            unset($_SESSION['carrito']);

            $carro_compra = [];
        }



    }catch (PDOException $e){
        echo $e->getMessage();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Listar productos</h2>
 
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            
            <?php foreach( $productos as $producto):?>
                <tr>
                    <td><?php echo $producto['id'] ?></td>
                    <td><?php echo $producto['nombre'] ?></td>
                    <td><?php echo $producto['precio'] ?></td>
                    <td><?php echo $producto['stock'] ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                            <button type="submit" name="anyadir">Añadir al carrito</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <h3>Carrito ----Total carrito: <?php echo count($carro_compra) ?></h3>

    <?php 
        $total = 0;
        foreach($carro_compra as $productos):
            echo $productos['nombre'];
            echo " - " . $productos['precio'] . "<br>";

            $total += $productos['precio'];
        
            
        endforeach;
    ?>
    <br>
    <p><strong>Precio total: <?php echo $total ?></strong></p>
    <hr>
    <br>
    <form method="post">
        <button type="submit" name="vaciar">Vaciar carrito</button>
    </form>
</body>
</html>
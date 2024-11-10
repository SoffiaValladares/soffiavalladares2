<?php
$conexion = new mysqli("localhost", "root", "", "Restaurante_5L1");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];
    $tabla = $_POST['tabla'];

    if ($accion === 'actualizar') {
        $id = $_POST['id'];

        if ($tabla == 'clientes') {
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $sql = "UPDATE clientes SET nombre = ?, telefono = ? WHERE id_cliente = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $telefono, $id);
        } elseif ($tabla == 'pedidos') {
            $subtotal = $_POST['subtotal'];
            $iva = $_POST['iva'];
            $total = $subtotal + $iva;
            $sql = "UPDATE pedidos SET subtotal = ?, iva = ?, total = ? WHERE id_pedido = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("dddi", $subtotal, $iva, $total, $id);
        }

        if ($stmt->execute()) {
            echo "Registro actualizado exitosamente.";
        } else {
            echo "Error al actualizar el registro: " . $conexion->error;
        }
        

        $stmt->close();
    }
}

$conexion->close();

header("Location: index.php?tabla=$tabla");
exit();
?>


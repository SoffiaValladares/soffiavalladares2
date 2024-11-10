<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante de Mariscos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <aside>
            <h2>Sistema</h2>
            <nav>
                <ul>
                    <li><a href="?tabla=clientes">Clientes</a></li>
                    <li><a href="?tabla=pedidos">Pedidos</a></li>
                    <li><a href="?tabla=menu">Menú</a></li>
                    <li><a href="?tabla=proveedores">Proveedores</a></li>
                    <li><a href="?tabla=inventario">Inventario</a></li>
                </ul>
            </nav>
        </aside>

        <section>
            <header>
                <h1>Restaurante de Mariscos</h1>
            </header>

            <main>
                <?php
                $conexion = new mysqli("localhost", "root", "", "Restaurante_5L1");

                if ($conexion->connect_error) {
                    die("Error de conexión: " . $conexion->connect_error);
                }

                if (isset($_GET['tabla'])) {
                    $tabla = $_GET['tabla'];
                    echo "<h2>Tabla: " . ucfirst($tabla) . "</h2>";

                    if (isset($_GET['accion']) && $_GET['accion'] == 'actualizar' && isset($_GET['id'])) {
                        $id = $_GET['id'];

                        $resultado = $conexion->query("SELECT * FROM $tabla WHERE id_cliente = $id");
                        $registro = $resultado->fetch_assoc();

                        echo "<form action='acciones.php' method='POST'>";
                        echo "<input type='hidden' name='tabla' value='$tabla'>";
                        echo "<input type='hidden' name='accion' value='actualizar'>";
                        echo "<input type='hidden' name='id' value='$id'>";

                        if ($tabla == 'clientes') {
                            echo "Nombre: <input type='text' name='nombre' value='{$registro['nombre']}'><br>";
                            echo "Teléfono: <input type='text' name='telefono' value='{$registro['telefono']}'><br>";
                        } elseif ($tabla == 'pedidos') {
                            echo "Subtotal: <input type='number' name='subtotal' value='{$registro['subtotal']}'><br>";
                            echo "IVA: <input type='number' name='iva' value='{$registro['iva']}'><br>";
                        }
                        echo "<button type='submit'>Guardar Cambios</button>";
                        echo "</form>";
                    } else {
                        $resultado = $conexion->query("SELECT * FROM $tabla");
                        if ($resultado->num_rows > 0) {
                            echo "<table><thead><tr>";
                            while ($columna = $resultado->fetch_field()) {
                                echo "<th>{$columna->name}</th>";
                            }
                            echo "<th>Acciones</th></tr></thead><tbody>";
                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<tr>";
                                foreach ($fila as $valor) {
                                    echo "<td>{$valor}</td>";
                                }
                                echo "<td>
                                        <form action='index.php' method='GET' style='display:inline-block;'>
                                            <input type='hidden' name='id' value='{$fila['id_cliente']}'>
                                            <input type='hidden' name='tabla' value='$tabla'>
                                            <button type='submit' name='accion' value='actualizar'>Actualizar</button>
                                        </form>
                                        <form action='acciones.php' method='POST' style='display:inline-block;'>
                                            <input type='hidden' name='id' value='{$fila['id_cliente']}'>
                                            <input type='hidden' name='tabla' value='$tabla'>
                                            <button type='submit' name='accion' value='eliminar'>Eliminar</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<p>No hay registros en la tabla $tabla.</p>";
                        }
                    }
                } else {
                    echo "<p>Selecciona una tabla para gestionar sus registros.</p>";
                }
                $conexion->close();
                ?>
            </main>
        </section>
    </div>
</body>
</html>


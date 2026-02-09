<?php
require_once "funciones.php";
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = uniqid();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #e0e0e0;
            color: #000;
            padding: 40px;
            text-align: center;
        }

        h1 {
            color: #000;
            margin-bottom: 30px;
            text-transform: uppercase;
            border-bottom: 4px solid #000;
            display: inline-block;
            padding-bottom: 10px;
        }

        .dynamic-table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
            max-width: 1000px;
            background-color: #fff;
            border: 4px solid #000;
            box-shadow: 10px 10px 0px #000;
        }

        .dynamic-table thead {
            background-color: #000;
            color: white;
            text-transform: uppercase;
        }

        .dynamic-table th,
        .dynamic-table td {
            padding: 15px;
            text-align: center;
            border: 2px solid #000;
        }

        .dynamic-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        button {
            background-color: #000;
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.1s;
            margin: 5px;
            font-family: inherit;
            box-shadow: 4px 4px 0px #555;
        }
        
        button:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #555;
        }

        #boton {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>
<?php
if (!isset($_SESSION['admin'])) {
    echo "<h1>Entra aqui mediante el panel de admin</h1>";
    echo '<a href="index.php"><button>Inicio</button></a><br>';
    die();
}
?>

<body style="text-align: center;">
    <h1>ZONA ADMIN</h1>
    <h1>TODOS LOS CURSOS</h1>
    <div id="boton">
        <a href="creaCurso.php"><button>Crear Curso</button></a>
        <a href="calculaPuntos.php"><button>Calcular Puntos</button></a>
        <a href="darPlazas.php"><button>Dar Plazas</button></a>
    </div>
    <?php
    require_once "funciones.php";
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT * FROM cursos");
    $boton = false;
    if (isset($_SESSION['admin'])) {
        $boton = true;
    }
    echo "<table class='dynamic-table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Código</th>";
    echo "<th>Nombre</th>";
    echo "<th>Abierto</th>";
    echo "<th>Plazas</th>";
    echo "<th>Plazo</th>";
    if ($boton) {
        echo "<th>Estado</th>";
        echo "<th>Eliminar</th>";
    }
    echo "</thead>";
    echo "</tr>";
    echo "<tbody>";
    $fecha = new DateTime('2026-02-03');
    $hoy = $fecha->format("Y-m-d");
    $sentencia->execute();
    while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $fila['codigo'] . "</td>";
        echo "<td>" . $fila['nombre'] . "</td>";
        echo "<td>" . $fila['abierto'] . "</td>";
        echo "<td>" . $fila['numeroplazas'] . "</td>";
        echo "<td>" . $fila['plazoinscripcion'] . "</td>";
        if ($boton) {
            echo "<td><a href='activar.php?id={$fila['codigo']}'><button style='background:#fff; color:#000; border:2px solid #000; box-shadow:none;'>Activar/Desactivar</button></a></td>";
            echo "<td><a href='eliminar.php?id={$fila['codigo']}'><button style='background:#fff; color:#000; border:2px solid #000; box-shadow:none;'>Eliminar</button></a></td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
    ?>
    <br>
    <a href="index.php"><button>Inicio</button></a><br>
</body>

</html>

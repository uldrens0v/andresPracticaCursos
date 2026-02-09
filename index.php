<?php
require_once "funciones.php";
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = uniqid();
}
if (isset($_SESSION['user'])) {
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT nombre,apellidos from solicitantes where dni= :dni");
    $sentencia->execute([":dni" => $_SESSION['user']]);
    $nombre = $sentencia->fetch(PDO::FETCH_ASSOC);
    echo "<div style='background:#000; color:#fff; padding:10px; text-align:center; font-family:monospace;'>Bienvenid@ " . $nombre['nombre'] . " " . $nombre['apellidos'] . "!</div>";
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

        .admitidos {
            background-color: #fff;
            color: #000;
            padding: 8px 15px;
            border: 2px solid #000;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            font-family: inherit;
        }
        
        .admitidos:hover {
            background-color: #000;
            color: #fff;
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
            margin: 10px;
            background-color: #000;
            color: white;
            padding: 12px 24px;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 5px 5px 0px #555;
            transition: transform 0.1s;
        }
        
        button:active {
            transform: translate(2px, 2px);
            box-shadow: 3px 3px 0px #555;
        }
        
        a { text-decoration: none; }
    </style>
</head>

<body>
    <h1>Cursos disponibles</h1>
    <?php
    require_once "funciones.php";
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT * FROM cursos");
    $boton = false;
    if (isset($_SESSION['user'])) {
        $boton = true;
    }
    echo "<table class='dynamic-table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Código</th>";
    echo "<th>Nombre</th>";
    echo "<th>Plazas</th>";
    echo "<th>Plazo</th>";
    if ($boton) {
        echo "<th>Acción</th>";
    }
    echo "<th>Admitidos</th>";
    echo "</thead>";
    echo "</tr>";
    echo "<tbody>";
    $fecha = new DateTime('2026-02-03');
    $hoy = $fecha->format("Y-m-d");
    $sentencia->execute();
    while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        if (($fila['abierto']) == 1 && ($fila['plazoinscripcion'] >= $hoy) && !($fila['numeroplazas'] <= 0)) {
            echo "<tr>";
            echo "<td>" . $fila['codigo'] . "</td>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['numeroplazas'] . "</td>";
            echo "<td>" . $fila['plazoinscripcion'] . "</td>";
            if ($boton) {
                echo "<td><a href='gestionInscribir.php?id={$fila['codigo']}'><button style='box-shadow:none; border:2px solid #000; background:#fff; color:#000;'>Inscribirme</button></a></td>";
            }
            echo "<td><a href='listaAdmitidos.php?id={$fila['codigo']}'><button class='admitidos'>Admitidos</button></a></td>";
            echo "</tr>";
        }
    }
    echo "</tbody></table>";
    ?>
    <br>
    <a href="login.php"><button>LOGIN</button></a>
    <a href="inscripcion.php"><button>REGISTRO</button></a>
    <a href="admin.php"><button>ZONA ADMIN</button></a>

</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            text-align: center;
            font-family: 'Courier New', Courier, monospace;
            background-color: #e0e0e0;
            color: #000;
            padding: 40px;
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
            margin: 0 auto;
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
            padding: 15px 30px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: transform 0.1s;
            margin-top: 30px;
            font-family: inherit;
            box-shadow: 5px 5px 0px #555;
        }
        
        button:active {
            transform: translate(2px, 2px);
            box-shadow: 3px 3px 0px #555;
        }
    </style>
</head>

<body>
    <?php

    require_once 'funciones.php';
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT * FROM solicitudes where admitido = 1 and codigocurso= :codigocurso");
    $sentencia->execute([":codigocurso" => $_GET['id']]);
    $consulta = $conexion->prepare("SELECT nombre from cursos where codigo = :codigocurso");
    $consulta->execute([":codigocurso" => $_GET['id']]);
    $nombreCurso = $consulta->fetchColumn();
    echo "<h1>Admitidos en " . $nombreCurso . "</h1>";
    echo "<table class='dynamic-table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>DNI</th>";
    echo "<th>NOMBRE</th>";
    echo "<th>CODIGOCURSO</th>";
    echo "<th>FECHASOLICITUD</th>";
    echo "</thead>";
    echo "</tr>";
    echo "<tbody>";
    while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        $query = $conexion->prepare("SELECT nombre,apellidos from solicitantes where dni = :dni");
        $query->execute([":dni" => $fila['dni']]);
        $nombreAdmitido = $query->fetch(PDO::FETCH_ASSOC);
        $nombreMostrar = $nombreAdmitido['apellidos'] . " ," . $nombreAdmitido['nombre'];
        echo "<tr>";
        echo "<td>" . $fila['dni'] . "</td>";
        echo "<td>" . $nombreMostrar . "</td>";
        echo "<td>" . $fila['codigocurso'] . "</td>";
        echo "<td>" . $fila['fechasolicitud'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    ?>
    <a href="index.php"><button type="button">Inicio</button></a>

</body>

</html>

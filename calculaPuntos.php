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
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        echo "<h1>Entra aqui mediante el panel de admin</h1>";
        echo '<a href="index.php"><button>Inicio</button></a><br>';
        die();
    }
    require_once 'funciones.php';
    $conexion = obtenerConexion();
    $consulta = $conexion->prepare("SELECT * FROM solicitantes");
    $consulta->execute();
    echo "<table class='dynamic-table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>NOMBRE</th>";
    echo "<th>APELLIDOS</th>";
    echo "<th>PUNTOS</th>";
    echo "</thead>";
    echo "</tr>";
    echo "<tbody>";
    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
        $puntos = 0;
        if ($fila['coordinadortic'] == 1) {
            $puntos += 4;
        }
        if ($fila['grupotic'] == 1) {
            $puntos += 3;
        }
        if ($fila['pbilin'] == 1) {
            $puntos += 3;
        }
        if ($fila['cargo'] == 1) {
            $cargos = ["Director", "Jefe De Estudios", "Secretario"];
            if (in_array($fila['nombrecargo'], $cargos)) {
                $puntos += 2;
            } else if ($fila['nombrecargo'] == "Jefe de Departamento") {
                $puntos += 1;
            }
        }
        $hoy = new DateTime('now');
        $añoActual = $hoy->format("Y");
        if (isset($fila['fechanac'])) {
            $añoSolicitanteSTR = strtotime($fila['fechanac']);
        }
        $ano = date("Y", $añoSolicitanteSTR);
        $antiguo = ($añoActual - $ano);
        if ($antiguo > 15) {
            $puntos += 1;
        }
        if ($fila['situacion'] == 'activo') {
            $puntos += 1;
        }
        $update = $conexion->prepare("UPDATE solicitantes set puntos = :puntos where dni = :dni");
        $update->execute([":puntos" => $puntos, ":dni" => $fila['dni']]);
        $query = $conexion->prepare("SELECT nombre,apellidos,puntos from solicitantes where dni= :dni");
        $query->execute([":dni" => $fila['dni']]);
        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $fila['nombre'] . "</td>";
            echo "<td>" . $fila['apellidos'] . "</td>";
            echo "<td>" . $fila['puntos'] . "</td>";
            echo "</tr>";
        }
    }
    echo "</tbody></table>";
    ?>
    <a href="panelAdmin.php"><button type="button">Volver</button></a>

</body>

</html>

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
            text-align: center;
            padding: 50px;
        }
        
        h1 {
            text-transform: uppercase;
            border-bottom: 4px solid #000;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
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
            margin-top: 20px;
            font-family: inherit;
            box-shadow: 5px 5px 0px #555;
        }

        button:hover {
            background-color: #333;
        }

        button:active {
            transform: translate(2px, 2px);
            box-shadow: 3px 3px 0px #555;
        }
    </style>
</head>

<body style="text-align: center;">
    <?php
    require_once 'funciones.php';
    require './PHPMailer-master/src/Exception.php';
    require './PHPMailer-master/src/PHPMailer.php';
    require './PHPMailer-master/src/SMTP.php';
    session_start();
    if (isset($_SESSION['user'])) {
        if (crearSolicitud($_SESSION['user'], $_GET['id'], 0)) {
            $conexion = obtenerConexion();
            $sentencia = $conexion->prepare("SELECT nombre from cursos where codigo = :codigo");
            $sentencia->execute([":codigo" => $_GET['id']]);
            $nombre = $sentencia->fetchColumn();
            $cuerpo = "Se ha inscrito el alumno con DNI: " . $_SESSION['user'] . ", en el curso: " . $nombre;
            mandarCorreo("carmen@domenico.es", "Inscripcion", $cuerpo, null);
            echo "<h1>Solicitud Registrada</h1>";
            echo '<a href="index.php"><button>Inicio</button></a><br>';
        } else {
            echo "<h1>Ya estas inscrito</h1>";
            echo '<a href="index.php"><button>Inicio</button></a><br>';
        }
    }

    ?>
</body>

</html>

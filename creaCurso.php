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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #000;
            margin-bottom: 25px;
            font-size: 24px;
            text-align: center;
            border-bottom: 4px solid #000;
            display: inline-block;
            padding-bottom: 5px;
            text-transform: uppercase;
        }

        div.container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            padding: 40px;
            border: 4px solid #000;
            box-shadow: 10px 10px 0px #000;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            padding: 12px;
            border: 2px solid #000;
            font-size: 16px;
            background-color: #f0f0f0;
            color: #000;
            font-family: inherit;
            outline: none;
        }

        input:focus,
        select:focus {
            background-color: #fff;
        }

        label {
            font-weight: bold;
            color: #000;
            margin-top: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .radio-group {
            background-color: #f0f0f0;
            padding: 15px;
            border: 2px solid #000;
            margin: 10px 0;
        }

        .radio-group p {
            margin: 0 0 10px 0;
            font-weight: bold;
            color: #000;
            font-size: 14px;
            text-transform: uppercase;
        }

        .radio-group label {
            font-weight: normal;
            margin-right: 15px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            text-transform: none;
        }

        input[type='submit'] {
            background-color: #000;
            color: #fff;
            padding: 15px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-family: inherit;
            transition: background-color 0.2s;
        }
        
        input[type='submit']:hover {
            background-color: #333;
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
            margin-bottom: 20px;
            font-family: inherit;
        }
    </style>
    <?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        echo '<body><div class="container">';
        echo "<h1>Entra aquí mediante el panel de admin</h1><br>";
        echo '<a href="index.php"><button>Inicio</button></a>';
        echo '</div></body>';
        die();
    }
    ?>
</head>

<body>
    <div class="container">
        <a href="panelAdmin.php"><button>Volver</button></a><br>
        <h1>Creacion De Curso</h1><br>
        <form method="post" action="creaCurso.php">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <label>Nombre</label>
            <input type="text" name="nombre">
            <?php
            if (isset($_POST['enviar']) && empty($_POST['nombre'])) {
                echo "<p style='color: red; font-weight:bold;'>Introduce un nombre</p>";
            }
            ?>
            <label>numeroplazas</label>
            <input type="number" name="plazas">
            <?php
            if (isset($_POST['enviar']) && empty($_POST['plazas'])) {
                echo "<p style='color: red; font-weight:bold;'>Introduce una cantidad de plazas</p>";
            }
            ?>
            <label>Plazo</label>
            <input type="date" name="fecha">
            <?php
            if (isset($_POST['enviar']) && empty($_POST['fecha'])) {
                echo "<p style='color: red; font-weight:bold;'>Introduce una fecha valida</p>";
            }
            ?>
            <div class="radio-group">
                <p>¿Esta abierto al publico?</p>
                <label>
                    <input type="radio" name="abierto" value="1" checked> Sí
                    <input type="radio" name="abierto" value="0"> No
                </label>
            </div>
            <input type="submit" name="enviar" value="Enviar Datos">
        </form>

        <?php
        require_once 'funciones.php';
        if (isset($_POST['enviar']) && !empty($_POST['nombre']) && !empty($_POST['plazas']) && !empty($_POST['fecha'])) {
            if ($_SESSION['token'] == $_POST['token']) {
                try {
                    $conexion = obtenerConexion();
                    $sentencia = $conexion->prepare("INSERT INTO cursos (nombre,abierto,numeroplazas,plazoinscripcion) values (:nombre,:abierto,:numeroplazas,:plazoinscripcion)");
                    $datos = [
                        ":nombre" => $_POST['nombre'],
                        ":abierto" => $_POST['abierto'],
                        ":numeroplazas" => $_POST['plazas'],
                        ":plazoinscripcion" => $_POST['fecha']

                    ];
                    $sentencia->execute($datos);
                    echo "<div style='margin-top:20px; padding:10px; background:#000; color:#fff; text-align:center;'>Curso Creado!</div>";
                    echo "<br>";
                    echo '<a href="panelAdmin.php"><button>Inicio</button></a><br>';
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        ?>
    </div>
</body>


</html>

<?php
require_once 'funciones.php';
session_start();

$errores = [];
$registrado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
    if (empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        die("Error de seguridad: Token inválido.");
    }

    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $dni = $_POST['dni'] ?? '';
    $pass = $_POST['pass'] ?? '';
    
    if (empty($nombre)) {
        $errores['nombre'] = "Introduce un nombre";
    }
    if (empty($apellido)) {
        $errores['apellido'] = "Introduce un apellido";
    }
    if (empty($dni)) {
        $errores['dni'] = "Introduce un dni valido";
    }
    if (empty($pass)) {
        $errores['pass'] = "Introduce una contraseña";
    }

    if (empty($errores)) {
        $resultado = registrarSolicitante(
            $dni,
            $nombre,
            $apellido,
            $_POST['telefono'] ?? '',
            $pass,
            $_POST['correo'] ?? '',
            $_POST['codigocentro'] ?? '',
            $_POST['cordTIC'] ?? 0,
            $_POST['grupTIC'] ?? 0,
            $_POST['grupTICNombre'] ?? '',
            $_POST['bilingue'] ?? 0,
            $_POST['cargo'] ?? 0,
            $_POST['cargoNombre'] ?? '',
            $_POST['situacion'] ?? '',
            $_POST['fecha'] ?? '',
            $_POST['especialidad'] ?? '',
            0 
        );

        if ($resultado) {
            $registrado = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
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
            margin-bottom: 20px;
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
            font-size: 14px;
            text-transform: uppercase;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            font-size: 12px;
            margin-top: -10px;
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
            text-transform: uppercase;
            font-size: 14px;
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

        .success-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            padding: 20px;
            border: 2px solid #fff;
            box-shadow: 5px 5px 0px rgba(0,0,0,0.2);
            z-index: 1000;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php"><button>Inicio</button></a><br>
        <h1>Formulario de Registro</h1><br>
        
        <form method="post" action="inscripcion.php">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>">
            <?php if (isset($errores['nombre'])): ?>
                <p class="error-msg"><?php echo $errores['nombre']; ?></p>
            <?php endif; ?>

            <label>Apellidos</label>
            <input type="text" name="apellido" value="<?php echo $_POST['apellido'] ?? ''; ?>">
            <?php if (isset($errores['apellido'])): ?>
                <p class="error-msg"><?php echo $errores['apellido']; ?></p>
            <?php endif; ?>

            <label>DNI</label>
            <input type="text" name="dni" value="<?php echo $_POST['dni'] ?? ''; ?>">
            <?php if (isset($errores['dni'])): ?>
                <p class="error-msg"><?php echo $errores['dni']; ?></p>
            <?php endif; ?>

            <label>Contraseña</label>
            <input type="text" name="pass" value="<?php echo $_POST['pass'] ?? ''; ?>">
            <?php if (isset($errores['pass'])): ?>
                <p class="error-msg"><?php echo $errores['pass']; ?></p>
            <?php endif; ?>

            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?php echo $_POST['telefono'] ?? ''; ?>">

            <label>Correo electrónico</label>
            <input type="text" name="correo" value="<?php echo $_POST['correo'] ?? ''; ?>">

            <label>Código Centro</label>
            <input type="text" name="codigocentro" value="<?php echo $_POST['codigocentro'] ?? ''; ?>">

            <div class="radio-group">
                <p>¿Eres coordinador TIC?</p>
                <label><input type="radio" name="cordTIC" value="1" <?php echo (isset($_POST['cordTIC']) && $_POST['cordTIC'] == 1) ? 'checked' : ''; ?>> Sí</label>
                <label><input type="radio" name="cordTIC" value="0" <?php echo (!isset($_POST['cordTIC']) || $_POST['cordTIC'] == 0) ? 'checked' : ''; ?>> No</label>
                
                <p style="margin-top:15px;">¿Perteneces al grupo TIC?</p>
                <label><input type="radio" name="grupTIC" value="1" <?php echo (isset($_POST['grupTIC']) && $_POST['grupTIC'] == 1) ? 'checked' : ''; ?>> Sí</label>
                <label><input type="radio" name="grupTIC" value="0" <?php echo (!isset($_POST['grupTIC']) || $_POST['grupTIC'] == 0) ? 'checked' : ''; ?>> No</label><br>
                
                <input type="text" name="grupTICNombre" placeholder="Nombre del grupo TIC" style="margin-top:10px; width:90%;" value="<?php echo $_POST['grupTICNombre'] ?? ''; ?>">
                
                <p style="margin-top:15px;">¿Perteneces al grupo Bilingüe?</p>
                <label><input type="radio" name="bilingue" value="1" <?php echo (isset($_POST['bilingue']) && $_POST['bilingue'] == 1) ? 'checked' : ''; ?>> Sí</label>
                <label><input type="radio" name="bilingue" value="0" <?php echo (!isset($_POST['bilingue']) || $_POST['bilingue'] == 0) ? 'checked' : ''; ?>> No</label>
                
                <p style="margin-top:15px;">¿Tienes algún cargo en el centro?</p>
                <label><input type="radio" name="cargo" value="1" <?php echo (isset($_POST['cargo']) && $_POST['cargo'] == 1) ? 'checked' : ''; ?>> Sí</label>
                <label><input type="radio" name="cargo" value="0" <?php echo (!isset($_POST['cargo']) || $_POST['cargo'] == 0) ? 'checked' : ''; ?>> No</label>
                <br>
                
                <select name="cargoNombre" style="margin-top:10px; width:100%;">
                    <?php 
                    $cargos = ['Director', 'Jefe De Estudios', 'Secretario', 'Jefe de Departamento'];
                    foreach ($cargos as $c) {
                        $selected = (isset($_POST['cargoNombre']) && $_POST['cargoNombre'] == $c) ? 'selected' : '';
                        echo "<option $selected>$c</option>";
                    }
                    ?>
                </select>
            </div>

            <select name="situacion">
                <option <?php echo (isset($_POST['situacion']) && $_POST['situacion'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                <option <?php echo (isset($_POST['situacion']) && $_POST['situacion'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
            </select>

            <label>Fecha Nacimiento</label>
            <input type="date" name="fecha" value="<?php echo $_POST['fecha'] ?? ''; ?>">

            <label>Especialidad</label>
            <input type="text" name="especialidad" value="<?php echo $_POST['especialidad'] ?? ''; ?>">

            <input type="submit" name="enviar" value="Enviar Datos">
        </form>
    </div>

    <?php if ($registrado): ?>
        <div class="success-box">Usuario registrado!</div>
    <?php endif; ?>
</body>

</html>

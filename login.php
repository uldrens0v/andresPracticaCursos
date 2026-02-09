<?php
require_once 'funciones.php';
session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = uniqid();
}

$dni = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
    $dni = $_POST['dni'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if (autenticarUsuario($dni, $pass, "solicitantes")) {
        $_SESSION['user'] = $dni;
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            background-color: #fff;
            padding: 40px;
            border: 4px solid #000;
            width: 100%;
            max-width: 350px;
            text-align: center;
            box-shadow: 10px 10px 0px #000;
        }

        h2 {
            color: #000;
            font-size: 24px;
            margin-bottom: 30px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        label {
            font-weight: bold;
            color: #000;
            font-size: 14px;
            margin-bottom: 5px;
            margin-top: 15px;
            text-transform: uppercase;
        }

        input {
            padding: 12px;
            border: 2px solid #000;
            font-size: 16px;
            outline: none;
            background: #f0f0f0;
            font-family: inherit;
        }

        input:focus {
            background: #fff;
        }

        a button, input[type='submit'] {
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
            transition: transform 0.1s;
        }

        a button:hover, input[type='submit']:hover {
            background-color: #333;
        }
        
        a button:active, input[type='submit']:active {
            transform: scale(0.98);
        }

        .error-msg {
            color: red;
            font-weight: bold;
            margin-top: 15px;
            text-transform: uppercase;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="login.php">
            <label>DNI</label>
            <input type="text" name="dni" value="
            <?php
            echo $dni;
            ?>">
            <label>Contraseña</label>
            <input type="text" name="pass"> 
            <input type="submit" name="enviar" value="Entrar">
        </form>
        
        <?php if (!empty($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <a href="index.php"><button type="button">Inicio</button></a>
    </div>
</body>

</html>

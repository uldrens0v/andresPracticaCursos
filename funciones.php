<?php
function obtenerConexion()
{
    $servidor = 'localhost';
    $baseDatos   = 'cursoscp';
    $usuario = 'web';
    $clave = 'web';

    try {
        $dsn = "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4";
        $conexion = new PDO($dsn, $usuario, $clave);

        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $conexion;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}
function autenticarUsuario($usuario, $clave, $tabla)
{
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT pass from $tabla where dni=:dni");
    $sentencia->execute([':dni' => $usuario]);
    $datosUsuario = $sentencia->fetch(PDO::FETCH_ASSOC);
    if ($datosUsuario) {
        if ($clave == $datosUsuario['pass']) {
            echo "<p style='color: green;'>Correcto</p>";
            return true;
        } else {
            echo "<p style='color: red;'>Contraseña Incorrecta</p>";
        }
    } else {
        echo "<p style='color: red;'>Usuario Incorrecto</p>";
    }
    $conexion = null;
}

function registrarSolicitante($dni, $nombre, $apellidos, $telefono, $clave, $correo, $codcen, $coordinadortic, $grupotic, $nomgrupo, $pbilin, $cargo, $nombrecargo, $situacion, $fechanac, $especialidad, $puntos)
{
    $conexion = obtenerConexion();
    if (empty($fechanac)) {
        $hoy = new DateTime('2026-02-03');
        $fechanac = $hoy->format("Y-m-d");
    }
    $datos = [
        ':dni'            => $dni,
        ':nombre'         => $nombre,
        ':apellidos'      => $apellidos,
        ':telefono'       => $telefono,
        ':pass'           => $clave,
        ':correo'         => $correo,
        ':codcen'         => $codcen,
        ':coordinadortic' => $coordinadortic,
        ':grupotic'       => $grupotic,
        ':nomgrupo'       => $nomgrupo,
        ':pbilin'         => $pbilin,
        ':cargo'          => $cargo,
        ':nombrecargo'    => $nombrecargo,
        ':situacion'      => $situacion,
        ':fechanac'       => $fechanac,
        ':especialidad'   => $especialidad,
        ':puntos'         => $puntos
    ];
    $sentencia = $conexion->prepare("INSERT into solicitantes (dni,nombre,apellidos,telefono,pass,correo,codcen,coordinadortic,grupotic,nomgrupo,pbilin,cargo,nombrecargo,situacion,fechanac,especialidad,puntos)
     values (:dni,:nombre,:apellidos,:telefono,:pass,:correo,:codcen,:coordinadortic,:grupotic,:nomgrupo,:pbilin,:cargo,:nombrecargo,:situacion,:fechanac,:especialidad,:puntos);");
    try {
        $sentencia->execute($datos);
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
function crearSolicitud($dni, $codigocurso, $admitido)
{
    $conexion = obtenerConexion();
    $hoy = new DateTime('2026-02-03');
    $fechasolicitud = $hoy->format("Y-m-d");
    $datos = [
        ':dni'            => $dni,
        ':codigocurso'         => $codigocurso,
        ':fechasolicitud'         => $fechasolicitud,
        ':admitido'         => $admitido,
    ];
    $sentencia = $conexion->prepare("INSERT INTO solicitudes (dni,codigocurso,fechasolicitud,admitido) values (:dni,:codigocurso,:fechasolicitud,:admitido)");
    try {
        $sentencia->execute($datos);
        return true;
    } catch (Exception $e) {
    }
}
function mandarCorreo($email, $asunto, $cuerpo, $adjunto)
{
    $destinatarios = $email;
    $correo = new PHPMailer();
    $correo->SMTPDebug = 0;
    $correo->isSMTP();
    $correo->Mailer = "SMTP";
    $correo->SMTPAuth = false;
    $correo->isHTML(true);
    $correo->SMTPAutoTLS = false;
    $correo->Port = 25;
    $correo->CharSet = 'UTF-8';
    $correo->Host = "localhost";
    $correo->Username = "acarrerofraile@gmail.com";
    $correo->Password = "000000000";
    $correo->setFrom('acarrerofraile@gmail.com');

    if (isset($adjunto)) {
        $correo->addAttachment($adjunto);
    }
    if (is_array($email)) {
        foreach ($destinatarios as $direccion) {
            $correo->addAddress($direccion);
        }
    } else {
        $correo->addAddress($email);
    }
    $correo->Subject = $asunto;
    $correo->Body = $cuerpo;

    if (!$correo->send()) {
        echo $correo->ErrorInfo;
    } else {
    }
}

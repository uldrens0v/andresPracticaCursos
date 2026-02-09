    <?php
    session_start();
    if (!isset($_SESSION['admin'])) {
        echo "<h1>Entra aqui mediante el panel de admin</h1>";
        echo '<a href="index.php"><button>Inicio</button></a><br>';
        die();
    }
    require_once 'funciones.php';
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT dni, puntos,
        (SELECT MAX(admitido) FROM solicitudes WHERE solicitudes.dni = solicitantes.dni) as admitido
        FROM solicitantes");
    $sentencia->execute();
    $admitidos = [];
    $noAdmitidos = [];
    while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
        if ($fila['admitido'] == 1) {
            $admitidos[$fila['dni']] = $fila['puntos'];
        } else {
            $noAdmitidos[$fila['dni']] = $fila['puntos'];
        }
    }
    arsort($noAdmitidos);
    arsort($admitidos);
    $cola = $noAdmitidos + $admitidos;
    $plazasRestantes = [];
    foreach ($cola as $dni => $puntos) {
        $query = $conexion->prepare("SELECT codigocurso,
            (SELECT numeroplazas FROM cursos WHERE cursos.codigo = solicitudes.codigocurso LIMIT 1) as plazas
            FROM solicitudes
            WHERE dni = :dni");
        $query->execute([':dni' => $dni]);
        while ($linea = $query->fetch(PDO::FETCH_ASSOC)) {
            $curso = $linea['codigocurso'];
            if (!isset($plazasRestantes[$curso])) {
                $plazasRestantes[$curso] = $linea['plazas'];
            }
            if ($plazasRestantes[$curso] > 0) {
                $plazasRestantes[$curso]--;
                $update = $conexion->prepare("UPDATE solicitudes set admitido = 1 where dni= :dni");
                $update2 = $conexion->prepare("UPDATE cursos set numeroplazas = numeroplazas-1 where codigo = :codigo");
                $update->execute([":dni" => $dni]);
                $update2->execute([":codigo" => $curso]);
            }
        }
    }
    header("Location: panelAdmin.php");
    ?>

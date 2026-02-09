<?php
require_once 'funciones.php';
try {
    $conexion = obtenerConexion();
    $sentencia = $conexion->prepare("SELECT abierto from cursos where codigo = :codigo");
    $sentencia->execute([":codigo" => $_GET['id']]);
    $abierto = $sentencia->fetchColumn();
    $estado = 0;
    if ($abierto == 1) {
        $estado = 0;
    } else if ($abierto == 0) {
        $estado = 1;
    }
    $actualizar = $conexion->prepare("UPDATE cursos set abierto = :estado where codigo = :codigo");
    $actualizar->execute([":estado" => $estado, ":codigo" => $_GET['id']]);
    header("Location: panelAdmin.php");
} catch (Exception $e) {
    echo $e->getMessage();
}

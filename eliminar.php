<?php
require_once 'funciones.php';
$conexion = obtenerConexion();
$sentencia = $conexion->prepare("DELETE from cursos where codigo = :codigo");
$sentencia->execute([":codigo" => $_GET['id']]);
header("Location: panelAdmin.php");

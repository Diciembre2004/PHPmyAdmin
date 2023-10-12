<?php
//creamos conexion a base de datos, host, usuario, contraseña y nombre de la base de datos
$conn = new mysqli("localhost", "root", "", "almacen");


//si da error, se valida. Mostraremos un eco y terminara el proceso de este script
if($conn -> connect_error){
    die('Error de conexion' . $conn -> connect_error); //se concatena paara saber que esta dando error
}
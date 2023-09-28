<?php

//aca haremos nuestras consultas
require 'config.php'; //la que contiene nuestra conexion a la base de datos

//crear una plantilla para usar en otras tablas

$columns = ['no_emp','fecha_nacimiento','nombre','apellido','fecha_ingreso'	]; 
$columnsWhere = ['no_emp', 'nombre','apellido'];
$table = 'empleados';


// un filto que haremos, se recibe por el metodo POST. Se debe limpiar la variable para que no
// inyecte codigo malicioso y hacer una validacion de que exista ese campo.
// 
$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']): null;

//construccion de fltros
$where = '';
if($campo != null){ //si viene vacio no tiene que hacer un filtro
    $where ='WHERE (';

    //contrar cuantas columnas tiene
    $cont = count($columnsWhere);
    for($i = 0; $i < $cont; $i++){
        $where .= $columnsWhere[$i]. "LIKE '%". $campo . "%' OR "; //OR que no solo busque en el nombre sino apellido
    } //LIKE '%tian%' buscar esta palabra sin importar su orden en los campos
    $where = substr_replace($where, "", -3); //remplaca menos tres por nada, o sea lo elimina
    //permite remplaczar un apartado con otro caracter
    $where .= ")";
    
}

//generar una consulta simple e indicar las columnas que se seleccionaran
//conversion implode convierte un array en un string y se puede separar en un caracter elegido, porej ;
//
$sql = "SELECT " . implode(", ", $columns) . "
FROM $table
$where ";
//imprimir consulta
echo $sql;
exit; //para que no continue con la ejecucion

$resultado = $conn->query($sql);
//muestra cuanto resultados nos esta trayendo la consulta para poder hacer una validacion
$num_rows = $resulado->$num_rows;

$html = '';

//si es mayor a 0 si esta trayendo resultados. 
if($num_row > 0){
    //en caso de que si, trae todas las final y fila por fila se genera filas con <td> y columnas
    while($row = $resultado->fetch_assoc()){
        $html .= '<tr>';
        $html .= '<td>' . $row['no_emp']. '</td>';
        $html .= '<td>' . $row['fecha_nacimiento']. '</td>';
        $html .= '<td>' . $row['nombre']. '</td>';
        $html .= '<td>' . $row['apellido']. '</td>';
        $html .= '<td>' . $row['apellido']. '</td>';
        $html .= '<td><a href="">Editar</a></td>';
        $html .= '<td><a href="">Eliminar</a></td>';
        $html .= '</tr>';
    }
} else{
    $html .= '<tr>';
    $html .= '<td colspan="7">Sin resultados</td>';
    $html .= '</tr>';
}

//retornar como json para que la peticion axaj lo pueda leer
//caracter expeciales JSON_UNESCAPED_UNICODE para que lo reconozca
echo json_encode($html, JSON_UNESCAPED_UNICODE);
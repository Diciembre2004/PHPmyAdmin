<?php

//aca haremos nuestras consultas
require 'config.php'; //la que contiene nuestra conexion a la base de datos

//crear una plantilla para usar en otras tablas
$columns = ['no_emp', 'nombre' , 'apellido', 'fecha_nacimiento', 'fecha_ingreso'	]; 
$table = "empleados" ;
$columnsWhere = ['no_emp', 'nombre','apellido'];
$table = 'empleados';
$id = 'no_emp';

// un filto que haremos, se recibe por el metodo POST. Se debe limpiar la variable para que no
// inyecte codigo malicioso y hacer una validacion de que exista ese campo.
$campo = isset($_POST['campo']) ? $conn->real_escape_string($_POST['campo']) : null;

//construccion de fltros
$where = '';
if($campo != null){ //si viene vacio no tiene que hacer un filtro
    $where = ' WHERE (';

    $cont = count($columns); //contrar cuantas columnas tiene
    for($i = 0; $i < $cont; $i++){
        $where .= $columns[$i]. " LIKE '%" . $campo . "%' OR "; //OR que no solo busque en el nombre sino apellido
    } //LIKE '%tian%' buscar esta palabra sin importar su orden en los campos
    $where = substr_replace($where, "", -3); //remplaca menos tres por nada, o sea lo elimina
    $where .= ")"; //permite remplaczar un apartado con otro caracter
    
}
//limite -----------------------
$limit = isset($_POST['registros']) ? $conn->real_escape_string($_POST['registros']): 10;
$pagina = isset($_POST['pagina']) ? $conn->real_escape_string($_POST['pagina']): 0;

if (!$pagina){
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $limit;
}

$sLimit = " LIMIT " . $inicio . " , " . $limit;


//ordenar
$sOrder = "";
if(isset($_POST['orderCol'])){
    $orderCol = $_POST['orderCol'];
    $orderType = isset($_POST['orderType']) ? $_POST['orderType'] : 'asc';

    $sOrder = "ORDER BY" . $columns[intval($orderCol)] . '' . $orderType;
}



//generar una consulta simple e indicar las columnas que se seleccionaran
//conversion implode convierte un array en un string y se puede separar en un caracter elegido, porej ;
$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . 
" FROM " . $table . $where . $sOrder . $sLimit; //imprimir consulta
$resultado = $conn->query($sql);
$num_row = $resulado->$num_row; //muestra cuanto resultados nos esta trayendo la consulta para poder hacer una validacion

//consulta para total de registros filtrados
$sqlFiltro = "SELECT FOUND_ROWS()";
$resFiltro = $conn->query($sqlFiltro);
$row_filtro = $resFiltro->fetch_array();
$totalFiltro = $row_filtro[0];

$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $conn->query($sqlTotal);
$row_total = $resTotal->fetch_array();
$totalRegistros = $row_total[0];

//Mmuestra resultados
$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';

if($num_row > 0){ //si es mayor a 0 si esta trayendo resultados. 
    while($row = $resultado->fetch_assoc()) { //en caso de que si, trae todas las final y fila por fila se genera filas con <td> y columnas
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['no_emp'] . '</td>';
        $output['data'] .= '<td>' . $row['nombre'] . '</td>';
        $output['data'] .= '<td>' . $row['apellido'] . '</td>';
        $output['data'] .= '<td>' . $row['fecha_nacimiento'] . '</td>';
        $output['data'] .= '<td>' . $row['fecha_ingreso'] . '</td>';
        $output['data'] .= '<td><a class="btn btn-warning btn-sm" href="editar.php?id=' . $row['no_emp'] . '">Editar</a></td>';
        $output['data'] .= '<td><a class="btn btn-danger btn-sm" href="eliminar.php?id=' . $row['no_emp'] . '">Eliminar</a></td>';
        $output['data'] .= '</tr>';
    }
} else{
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="7">Sin resultados</td>';
    $output['data'] .= '</tr>';
}

if($output['totalRegistros'] > 0) {
    $totalPaginas = ceil($output['totalRegistros'] / $limit); 

    $output['paginacion'] .= '<nav>'; 
    $output['paginacion'] .= '<ul class="pagination">'; 

    $numeroInicio = 1;
    if(($pagina - 4) > 1){
        $numeroInicio = $pagina - 4;
    }
    $numeroFin = $numeroInicio + 9; 

    if($numeroFin > $totalPaginas){ 
        $numeroFin = $totalPaginas;
    }

    for($i = $numeroInicio; $i <= $numeroFin; $i++){
        if($pagina == $i){
            $output['paginacion'] .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></
            li>';
        } else {
            $output['paginacion'] .= '<li class="page-item"><a class="page-link" href="#" onclick="nextPage(' . $i . 
            ')">' . $i . '</a></li>';
        }
    }

    $output['paginacion'] .= '</ul>'; 
    $output['paginacion'] .= '</nav>'; 
}

//retornar como json para que la peticion axaj lo pueda leer
//caracter expeciales JSON_UNESCAPED_UNICODE para que lo reconozca
echo json_encode($output, JSON_UNESCAPED_UNICODE);
echo json_encode("data");
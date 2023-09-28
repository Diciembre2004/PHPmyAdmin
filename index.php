<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacen</title>

    <style>
        table, th, td{border: 1px solid;}
        table{border-collapse: collapse; width: 80%;}
    </style>

</head>
<body>
    <h2>Empleados</h2>

    <form action="" method="post">
        <label for="campo">Buscar: </label>
        <input type="text" name="campo" id="campo">
    </form>

    <p></p>

    <table>
        <thead>
            <th>Numero de Empleados</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Fecha Nacimiento</th>
            <th>Fecha ingreso</th>
            <th>editar</th>
            <th>eliminar</th>
        </thead>

        <tbody id="content">

        </tbody>
    </table>

    <script>
        getData()

        //llamar evento
        document.getElementById("campo").addEventListener("keyup", getData)
        //hara la peticion
        function getData(){
            let input = document.getElementById("campo").value
            let content = document.getElementById("content")
            //url del archivo que hace la consulta
            let url = "load.php"
            //enviar parametros
            let formaData = new FormData()
            formaData.append("campo", input)

            fetch(url,{
                method: "POST",
                body: FormData 
            }).then(response => response.json()) //entonces obtiene json
            .then(data => {
                content.innerHTML = data
            }).catch(err => console.log(err)) //imprima los errores

        }
    </script>
</body>
</html>
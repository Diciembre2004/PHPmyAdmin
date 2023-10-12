<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Almacen</title>

    <link rel="icon" href="data:,">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"  integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"  crossorigin="anonymous">

    <style>
        table, th, td{border: 1px solid;}
        table{border-collapse: collapse; width: 80%;}
    </style>

</head>
<body>
    <main>
        <div class="container py-4 text-center">
            <h2>Empleados</h2>

            <div class="row g-4">
                <div class="col-auto">
                    <label for="num_registros" class="col-form-label">Mostrar: </label>
                </div>
                <div class="col-auto">
                    <select name="num_registros" id="num_registros" class="form_select"></select>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </div>

                <div class="col-auto">
                    <label for="num_registros" class="col-form-label">registros.</label>
                </div>

                <div class="col-4"></div>

                <div class="col-auto">
                    <label for="campo" class="col-form-label">Buscar: </label>
                </div>
                <div class="col-auto">
                    <input type="text" name="campo" id="campo" class="form-control">
                </div>
            </div>

            <div class="row py-4">
                <div class="col">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <th class="sort asc">Numero de Empleados</th>
                            <th class="sort asc">Nombre</th>
                            <th class="sort asc">Apellido</th>
                            <th class="sort asc">Fecha Nacimiento</th>
                            <th class="sort asc">Fecha ingreso</th>
                            <th></th>
                            <th></th>
                        </thead>

                        <tbody id="content">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-6"></div>
                    <label for="lbl-total"></label>
                </div>

                <div class="col-6" id="nav-paginacion"></div>

                <input type="hidden" id="pagina" value=1>
                <input type="hidden" id="orderCol" value=0>
                <input type="hidden" id="orderType" value=asc>
            </div>
        </div>
    </main>

    <script>
        getData()

        //llamar evento
        document.getElementById("campo").addEventListener("keyup", function() {
            getData(1)
        }, false)
        document.getElementById("num_registros").addEventListener("change", function() {
            getData()
        }, false)

        //hara la peticion
        function getData( ) {
            let input = document.getElementById("campo").value
            let num_registros = document.getElementById("num_registros").value
            let content = document.getElementById("content")
            let pagina = document.getElementById("pagina").value
            let orderCol = document.getElementById("orderCol").value
            let orderType = document.getElementById("orderType").value

            if(pagina == null) {
                pagina = 1
            }

            let url = "load.php" //url del archivo que hace la consulta
            let formaData = new FormData() //enviar parametros
            formaData.append('campo', input)
            formaData.append('registros', num_registros)
            formaData.append('pagina', pagina)
            formaData.append('orderCol', orderCol)
            formaData.append('orderType', orderType)

            fetch(url, {
                    method: "POST",
                    body: formaData 
                }).then(response => response.json()) //entonces obtiene json
                .then(data => {
                    content.innerHTML = data.data
                    document.getElementById("lbl-total").innerHTML = 'Mostrando ' + data.totalFiltro + 
                        'de ' + data.totalRegistros + ' registros'
                    document.getElementById("nav-paginacion").innerHTML = data.paginacion
                }).catch(err => console.log(err)) //imprima los errores

        }

        function nextPage(pagina) {
            document.getElementById('pagina').value = pagina 
            getData()
        }

        let columns = document.getElementsByClassName("sort")
        let tamaño = columns.length
        for(let i = 0; i < tamaño; i++){
            columns[i].addEventListener("click", ordenar)
        }

        function ordenar(e){
            console.log(e.target)
            let elemento = e.target

            document.getElementById('orderCol').value = elemento.cellIndex

            if(elemento.classList.contains("asc")){
                document.getElementById('orderType').value = "asc"
                elemento.classList.remove("asc")
                elemento.classList.add("desc")
            } else {
                document.getElementById('orderType').value = "desc"
                elemento.classList.remove("desc")
                elemento.classList.add("asc")
            }
            getData()
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
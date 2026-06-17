<?php
    require_once __DIR__ . "/../../config/database.php";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listar Categorias de publicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
  
    <table class="table">
    <thead>
        <tr>
        <th scope="col">id</th>
        <th scope="col">Nombre Categoria</th>
        <th scope="col">ID Funcionario</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $consulta = "SELECT * FROM categoria_publicacion";
            $resultado =$conexion->query($consulta);

            if($resultado->rowCount() > 0){
                foreach($resultado as $fila){
                    $id_categoria =$fila['id_categoria'];
                    $nombre =$fila['nombre'];
                    $id_funcionario =$fila['id_funcionario'];
                    echo "<tr>";
                        echo "<td>".$id_categoria."</td>";                
                        echo "<td>".$nombre."</td>";
                        echo "<td>".$id_funcionario."</td>";
                        echo "<td><a href='editar_categoria_publicacion?id_enviado=".$id_categoria."'>Editar </a></td>";
                        echo "<td><a href='eliminar_categoria_publicacion?id_enviado=".$id_categoria."'>Eliminar </a></td>";
                    echo "</tr>";
                }
            }else{
                echo    "<tr>
                            <td>No hay categorias</td>
                        </tr>";
            }
               
        ?>
    </tbody>
    </table>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
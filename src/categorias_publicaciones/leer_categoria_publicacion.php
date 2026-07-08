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
            $consulta = "SELECT cp.id_categoria , cp.nombre, u.nombre as nombre_p,u.apellido as apellido_p FROM categoria_publicacion cp JOIN funcionario_municipal f ON cp.id_funcionario = f.id_funcionario
            JOIN usuario u ON f.rut_usuario = u.rut ";
            $db = getDatabase();
            $resultado =$db->query($consulta);

            if(count($resultado) > 0){
                foreach($resultado as $fila){
                    $id_categoria =$fila['id_categoria'];
                    $nombre =$fila['nombre'];
                    $nombre_p =$fila['nombre_p'];
                    $apellido_p =$fila['apellido_p'];
                    echo "<tr>";
                        echo "<td>".$id_categoria."</td>";                
                        echo "<td>".$nombre."</td>";
                        echo "<td>".$nombre_p." ".$apellido_p."</td>";
                        echo "<td><a href='?ruta=editar_categoria_publicacion&id_enviado=".$id_categoria."'>Editar </a></td>";
                        echo "<td><a href='?ruta=eliminar_categoria_publicacion&id_enviado=".$id_categoria."'>Eliminar </a></td>";
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
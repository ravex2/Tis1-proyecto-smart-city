<?php
    require_once __DIR__ . "/../../config/database.php";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de publicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
  
    <table class="table">
    <thead>
        <tr>
        <th scope="col">id</th>
        <th scope="col">Titulo</th>
        <th scope="col">Descripcion</th>
        <th scope="col">Fecha Evento</th>
        <th scope="col">Estado</th>
        <th scope="col">Imagen</th>
        <th scope="col">Lugar</th>
        <th scope="col">Id Categoria</th>
        <th scope="col">Id Funcionario</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php

            $consulta = "SELECT p.*, c.nombre AS categoria_nombre FROM publicacion p
            JOIN categoria_publicacion c ON p.id_categoria = c.id_categoria";
            $resultado =$conexion->query($consulta);

            if($resultado->rowCount() > 0){
                foreach($resultado as $fila){
                    $id_publicacion =$fila['id_publicacion'];
                    $titulo =$fila['titulo'];
                    $contenido =$fila['contenido'];
                    $fecha_evento =$fila['fecha_evento'];
                    $tipo_estado =$fila['tipo_estado'];
                    $imagen =$fila['imagen'];
                    $lugar =$fila['lugar'];
                    $categoria_nombre =$fila['categoria_nombre'];
                    $id_funcionario =$fila['id_funcionario'];
                    echo "<tr>";
                        echo "<td>".$id_publicacion."</td>";                
                        echo "<td>".$titulo."</td>";
                        echo "<td>".$contenido."</td>";
                        echo "<td>".$fecha_evento."</td>";
                        echo "<td>".$tipo_estado."</td>";
                        echo "<td><img src='../src/publicaciones/".$fila['imagen']."' width='100'></td>";
                        echo "<td>".$lugar."</td>";
                        echo "<td>".$categoria_nombre."</td>";
                        echo "<td>".$id_funcionario."</td>";
                        echo "<td><a href='editar_publicacion?id_enviado=".$id_publicacion."'>Editar </a></td>";
                        echo "<td><a href='eliminar_publicacion?id_enviado=".$id_publicacion."'>Eliminar </a></td>";
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
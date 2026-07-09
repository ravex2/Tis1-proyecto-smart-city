<?php
require_once __DIR__ . "/../../config/database.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$consulta = "SELECT * FROM publicacion WHERE tipo_estado = 'activa'
ORDER BY fecha DESC";
$db = getDatabase();
$resultado = $db->query($consulta);

if (isset($_GET['voto_pub']) && isset($_GET['tipo_voto'])) {
    $id_p = intval($_GET['voto_pub']);
    $tipo_r = $_GET['tipo_voto'];
    
    // 1. Buscamos si ESTA publicación específica ya tiene alguna reacción registrada
    $reaccion_existente = $db->query("SELECT id_reaccion, tipo_reaccion FROM reaccion WHERE id_publicacion = ?",[$id_p]);

    $reaccion_existente = $reaccion_existente[0] ?? null;
    
    if ($reaccion_existente) {
        if ($reaccion_existente['tipo_reaccion'] === $tipo_r) {
            // CASO 3: eliminar reacción
            $db->execute("DELETE FROM reaccion WHERE id_reaccion = ?",[$reaccion_existente['id_reaccion']]);
        } else {
            // CASO 2: cambiar reacción
            $db->execute("UPDATE reaccion SET tipo_reaccion = ? WHERE id_reaccion = ?", [$tipo_r, $reaccion_existente['id_reaccion']] );
        }
    } else {
        // CASO 1: crear reacción
        $db->execute("INSERT INTO reaccion (id_publicacion, tipo_reaccion) VALUES (?, ?)",[$id_p, $tipo_r]);
    }
    // Redirección manteniendo la posición de la pantalla
    header("Location: ?ruta=publicaciones#post-" . $id_p);
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartCity - Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="social-style.css">
    <link rel="stylesheet" href="/Tis1-proyecto-smart-city/assets/css/publicaciones.css">
    
</head>
<body>

<div class="container custom-container">
    <div class="row gx-4">
        
        <aside class="col-md-3 d-none d-md-flex flex-column py-4 sticky-top vh-100">
            <div class="logo-area mb-4 px-3">
                <i class="bi bi-intersect fs-2 text-primary"></i>
            </div>
            <nav class="nav flex-column gap-2 mb-auto">
                <a class="nav-link" href="feed.html"><i class="bi bi-house-door me-3"></i> Inicio</a>
                <a class="nav-link active" href="#"><i class="bi bi-hash me-3"></i> Explorar</a>
                <a class="nav-link" href="#"><i class="bi bi-bell me-3"></i> Notificaciones</a>
                <a class="nav-link" href="#"><i class="bi bi-person me-3"></i> Perfil</a>
            </nav>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="card rounded-4 p-4 mb-4 shadow-sm">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:56px; height:56px;">
                            <?= strtoupper(substr($_SESSION['user']['nombre'] ?? '', 0, 1)) ?>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($_SESSION['user']['nombre'] . ' ' . $_SESSION['user']['apellido']) ?></h6>
                            <p class="mb-0 text-muted small"><?= htmlspecialchars($_SESSION['user']['correo']) ?></p>
                        </div>
                    </div>
                    <div class="text-muted small">Bienvenido al feed comunitario</div>
                </div>
            <?php else: ?>
                <a class="btn btn-primary rounded-pill py-3 fw-bold shadow-primary mb-4 w-100" href="?ruta=login">Iniciar Sesión</a>
            <?php endif; ?>

        </aside>

        
        <main class="col-md-6 border-start border-end px-0 bg-white shadow-sm min-vh-100">
            <div class="feed-header p-3 sticky-top bg-white-glass blur d-flex align-items-center gap-4">
                <a href="feed.html" class="btn-icon-soft sm"><i class="bi bi-arrow-left"></i></a>
                <h5 class="fw-bold mb-0">Publicaciones</h5>
            </div>
        <?php if(count($resultado) > 0){ ?>
        <?php   foreach($resultado as $fila){
            $id_pub = $fila['id_publicacion'];
            $total_me_guta = $db->query("SELECT COUNT(*) AS total FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'me gusta'")[0]['total'];
            $total_me_encanta = $db->query("SELECT COUNT(*) AS total FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'me encanta'")[0]['total'];
            $total_no_me_gusta = $db->query("SELECT COUNT(*) AS total FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'no me gusta'")[0]['total'];
            $total_me_divierte = $db->query("SELECT COUNT(*) AS total FROM reaccion WHERE id_publicacion = $id_pub AND tipo_reaccion = 'me divierte'")[0]['total'];
        ?>
            <div class="post-detail-content p-4 border-bottom">
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle shadow-sm" width="56" height="56">
                        <div>
                            <div class="post-main-text fs-5 fw-medium mb-3 lh-sm">
                                <?php echo $fila['titulo']; ?>
                            
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="post-main-text fs-4 fw-medium mb-3 lh-sm">
                                    <img src="/Tis1-proyecto-smart-city/src/publicaciones/img/<?php echo $fila['imagen']; ?>"
                                    class="img-fluid rounded-3" style="max-height:300px; object-fit:cover;">
                </div>
                <div class="fw-bold fs-6 mb-0">
                    <?php echo $fila['contenido']; ?>
                </div>
                

                <div class="text-muted small mb-3">
                    <span class="fw-bold text-dark">Lugar:</span> <?php echo $fila['lugar']; ?> |
                    <span class="fw-bold text-dark">Fecha:</span><?php echo !empty($fila['fecha_evento']) ? date("d-m-Y", strtotime($fila['fecha_evento'])) : 'Sin Fecha'; ?>
                </div>

                <hr class="opacity-10">

                <!-- Stats -->
                <div class="d-flex gap-4 py-1 border-bottom border-top border-light my-3 py-3">
                    <div>
                        <span class="fw-bold">0</span> <span class="text-muted">Comentarios</span>
                    </div> 
                    <div>
                        <span class="fw-bold"><?php echo $total_me_guta; ?></span> <span class="text-muted">Me gusta</span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $total_me_encanta; ?></span> <span class="text-muted">Me encanta</span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $total_no_me_gusta; ?></span> <span class="text-muted">No me gusta</span>
                    </div>
                    <div>
                        <span class="fw-bold"><?php echo $total_me_divierte; ?></span> <span class="text-muted">Me divierte</span>
                    </div>


                </div>

                <!-- Botones de Acción Grandes -->
                <div class="d-flex justify-content-around text-muted fs-5 py-1">
                    <i class="bi bi-chat action-hover-blue" onclick="toggleComments(<?php echo $id_pub; ?>)"></i>            <!-- Comentarios -->

                    <a href="?ruta=publicaciones&voto_pub=<?php echo $id_pub; ?>&tipo_voto=me gusta" class="text-muted action-hover-green text-decoration-none">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </a> <!-- Me gusta -->
                    <a href="?ruta=publicaciones&voto_pub=<?php echo $id_pub; ?>&tipo_voto=me encanta" class="text-muted action-hover-blue text-decoration-none">
                        <i class="bi bi-heart"></i>
                    </a> <!-- Me encanta -->
                    <a href="?ruta=publicaciones&voto_pub=<?php echo $id_pub; ?>&tipo_voto=no me gusta" class="text-muted action-hover-red text-decoration-none">
                        <i class="bi bi-hand-thumbs-down"></i>
                    </a> <!-- No me gusta -->
                    <a href="?ruta=publicaciones&voto_pub=<?php echo $id_pub; ?>&tipo_voto=me divierte" class="text-muted action-hover-green text-decoration-none">
                        <i class="bi bi-emoji-grin"></i>
                    </a>     <!-- Me divierte -->
                    
                </div>
            </div>
            
            <div id="comments-section-<?php echo $fila['id_publicacion']; ?>" class="d-none">
                <div class="comments-list bg-light-soft px-4 py-2" data-id="<?php echo $fila['id_publicacion']; ?>" id="comments-list-<?php echo $fila['id_publicacion']; ?>">
                    <!-- Comentarios cargados dinamicamente -->
                </div>

                <div class="comment-input-area p-3 border-bottom bg-light-soft">
                    <div class="d-flex gap-3">
                        <img src="https://i.pravatar.cc/150?u=3" class="rounded-circle" width="40" height="40">
                        <div class="flex-grow-1">
                            <input type="text" id="input-comment-<?php echo $fila['id_publicacion']; ?>" class="form-control border-0 bg-transparent py-2" placeholder="Publica tu respuesta">
                        </div>
                        <button onclick="addComment(<?php echo $fila['id_publicacion']; ?>)" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold shadow-primary">Responder</button>
                    </div>
                </div>
            </div>
            <?php  }?>
            <?php }?>
            
        </main>
            

        <aside class="col-md-3 d-none d-md-block py-4 sticky-top vh-100">
            <div class="search-bar mb-4">
                <div class="input-group bg-light rounded-pill px-3 border-0">
                    <span class="input-group-text bg-transparent border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 py-2" placeholder="Buscar en Reportes">
                </div>
            </div>



            <div class="trend-card bg-light rounded-4 p-3">
                <h6 class="fw-bold mb-3 px-1">Emprendedores Destacados</h6>
                <div class="follow-item d-flex align-items-center gap-2 mb-3">
                    <img src="https://i.pravatar.cc/150?u=shop1" class="rounded-circle" width="36" height="36">
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-truncate small">Café de la Plaza</div>
                        <div class="text-muted tiny">@plaza_cafe</div>
                    </div>
                    <button class="btn btn-dark btn-sm rounded-pill px-3">Ver</button>
                </div>
            </div>

        </aside>

    </div>
</div>

<script>
const mockRut = '12345678-9'; 

document.addEventListener('DOMContentLoaded', () => {
    const commentLists = document.querySelectorAll('.comments-list');
    commentLists.forEach(list => {
        loadComments(list.getAttribute('data-id'));
    });
});

function toggleComments(id_publicacion) {
    const section = document.getElementById(`comments-section-${id_publicacion}`);
    if (section) {
        section.classList.toggle('d-none');
    }
}

async function loadComments(id_publicacion) {
    try {
        const res = await fetch('src/gestion_comentarios/leer.php?id_publicacion=' + id_publicacion);
        const data = await res.json();
        
        const container = document.getElementById(`comments-list-${id_publicacion}`);
        if(container && data.length > 0) {
            container.innerHTML = '';
            data.forEach(comment => {
                container.innerHTML += renderComment(comment, id_publicacion);
            });
        }
    } catch(e) {
        console.error("Error cargando comentarios:", e);
    }
}

function renderComment(comment, id_publicacion) {
    return `
    <div class="comment-item d-flex gap-3 my-2" id="comment-${comment.id_comentario}">
        <img src="https://i.pravatar.cc/150?u=${comment.rut_usuario}" class="rounded-circle" width="32" height="32">
        <div class="flex-grow-1 bg-white p-2 rounded shadow-sm border border-light">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold text-dark small">${comment.rut_usuario}</span>
                <span class="text-muted" style="font-size:0.7rem;">${comment.fecha_comentario}</span>
            </div>
            <div class="comment-text mb-2 text-secondary" id="comment-text-${comment.id_comentario}" style="font-size:0.9rem;">${comment.comentario}</div>
            
            <div class="d-flex gap-2">
                <button onclick="editComment(${comment.id_comentario})" class="btn btn-sm btn-link text-decoration-none text-primary p-0" style="font-size:0.75rem;">Editar</button>
                <button onclick="deleteComment(${comment.id_comentario}, ${id_publicacion})" class="btn btn-sm btn-link text-decoration-none text-danger p-0" style="font-size:0.75rem;">Eliminar</button>
            </div>
            
            <div id="edit-box-${comment.id_comentario}" class="d-none mt-2">
                <input type="text" id="edit-input-${comment.id_comentario}" class="form-control form-control-sm mb-2" value="${comment.comentario}">
                <button onclick="saveEdit(${comment.id_comentario})" class="btn btn-sm btn-success py-0" style="font-size:0.8rem;">Guardar</button>
                <button onclick="cancelEdit(${comment.id_comentario})" class="btn btn-sm btn-secondary py-0" style="font-size:0.8rem;">Cancelar</button>
            </div>
        </div>
    </div>`;
}

async function addComment(id_publicacion) {
    const input = document.getElementById(`input-comment-${id_publicacion}`);
    const texto = input.value.trim();
    if(!texto) return;

    try {
        const res = await fetch('src/gestion_comentarios/insertar.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                comentario: texto,
                id_publicacion: id_publicacion,
                rut_usuario: mockRut
            })
        });
        const data = await res.json();
        
        if(data.success) {
            input.value = '';
            const container = document.getElementById(`comments-list-${id_publicacion}`);
            container.innerHTML += renderComment(data, id_publicacion);
        } else {
            alert(data.message || 'Error al agregar comentario');
        }
    } catch(e) {
        console.error("Error agregando:", e);
    }
}

function editComment(id_comentario) {
    document.getElementById(`comment-text-${id_comentario}`).classList.add('d-none');
    document.getElementById(`edit-box-${id_comentario}`).classList.remove('d-none');
}

function cancelEdit(id_comentario) {
    document.getElementById(`comment-text-${id_comentario}`).classList.remove('d-none');
    document.getElementById(`edit-box-${id_comentario}`).classList.add('d-none');
}

async function saveEdit(id_comentario) {
    const input = document.getElementById(`edit-input-${id_comentario}`);
    const nuevoTexto = input.value.trim();
    if(!nuevoTexto) return;

    try {
        const res = await fetch('src/gestion_comentarios/editar.php', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id_comentario: id_comentario,
                comentario: nuevoTexto
            })
        });
        const data = await res.json();
        
        if(data.success) {
            document.getElementById(`comment-text-${id_comentario}`).textContent = nuevoTexto;
            cancelEdit(id_comentario);
        } else {
            alert(data.message || 'Error al editar comentario');
        }
    } catch(e) {
        console.error("Error editando:", e);
    }
}

async function deleteComment(id_comentario, id_publicacion) {
    if(!confirm('¿Estás seguro de eliminar este comentario?')) return;

    try {
        const res = await fetch('src/gestion_comentarios/eliminar.php', {
            method: 'DELETE',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id_comentario: id_comentario })
        });
        const data = await res.json();
        
        if(data.success) {
            const element = document.getElementById(`comment-${id_comentario}`);
            if(element) element.remove();
        } else {
            alert(data.message || 'Error al eliminar comentario');
        }
    } catch(e) {
        console.error("Error eliminando:", e);
    }
}
</script>
</body>
</html>
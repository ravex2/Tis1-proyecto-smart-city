

# Procesos de refactorización y pruebas

## Resumen general
Esta aplicación sigue una aproximación MVC incompleta. El directorio `views/` contiene plantillas, pero también incluye lógica PHP de negocio, validaciones y acceso a datos. El directorio `src/` actúa como un segundo conjunto de páginas mixtas que duplican funciones de `views/` y por lo tanto debe reestructurarse.

Se recomienda:

- mover la lógica de negocio y el procesamiento de formularios a los controladores en `controllers/`
- usar `models/` para toda interacción con la base de datos
- mantener `views/` solo para renderizar HTML y mostrar variables inyectadas
- eliminar la mezcla `src/` / `views/` con el mismo propósito, dejando `src/` como código funcional tras una refactorización o consolidando su contenido en `views/` y controladores
- externalizar almacenamiento de medios y configuración sensible en servicios externos gestionados por AWS

## Qué se debe refactorizar

1. `views/base.php`
   - pasar el enrutamiento y el login a un controlador o a una capa de router ligera.
   - eliminar la lógica de control de rutas y de autenticación de la vista.
2. `views/pages/admin/*.php`
   - mover el procesamiento de `$_POST`/`$_GET` y la creación/edición/eliminación de registros a métodos del controlador correspondiente.
   - dejar estas páginas como plantillas de interfaz.
3. `src/` completo
   - los archivos de `src/publicaciones/`, `src/categorias_publicaciones/` y `src/gestion_comentarios/` contienen operaciones SQL y lógica de negocio.
   - refactorizar esos scripts para invocar controladores o métodos de modelo en lugar de ejecutar consultas directamente.
4. `views/layout/sidebar.php`
   - separar el cálculo de la ruta activa (`$_GET['ruta']`) de la plantilla.
5. Manejo de datos de entrada
   - evitar leer `$_POST` y `$_GET` directamente en vistas.
   - normalizar validación y saneamiento en el controlador/servicio.
6. Uso de modelos y controladores existentes
   - fortalecer `UsuarioController`, `SectorController`, `AuthController`, `PermisoController`, `RolController`, `SesionController`, etc.
   - garantizar que todas las rutas y acciones usen controladores e `include` o `require` controlados.
7. Reglas de negocio dispersas
   - todo cálculo de fechas, estados y parámetros de formulario debe centralizarse.
   - por ejemplo, `src/publicaciones/crear_publicacion.php` transforma `fecha_evento`; eso debe ocurrir en el controlador.
8. Externalización con AWS
   - almacenar archivos e imágenes en AWS S3, no con rutas locales de `imagen_1.jpg`.
   - mantener la configuración de AWS y credenciales fuera del código, en variables de entorno.
   - usar servicios locales para lógica de dominio y AWS solo para recursos externos (almacenamiento de objetos, CDN, etc.).




## Qué se debe testear

### Test unitarios

- Validación de formularios y datos recibidos en controladores.
- Métodos de modelo que realizan consultas SQL básicas.
- Reglas de negocio que calculan estados, fechas o resultados de voto.
- Manejo de credenciales y autenticación en `AuthController`.
- Transformaciones de datos para AWS S3 y paths de medios.

### Test de integración

- Flujo de registro, login y acceso a páginas protegidas.
- Creación, edición y eliminación de publicaciones, categorías, usuarios, rubros y sectores.
- Integración entre controladores y modelos: INSERT, UPDATE, DELETE y SELECT en MySQL.
- Carga de archivos o referencias a AWS S3 desde el frontend hacia el backend.
- Renderizado de vistas con datos entregados por controladores.

### Test de módulos

- Módulo de autenticación.
- Módulo de usuarios y roles.
- Módulo de publicaciones y categorías de publicaciones.
- Módulo de gestión de comentarios.
- Módulo de sectores / municipalidades.
- Módulo de panel administrativo.

## Todo list de pruebas

1. Modulo de autenticación
   - login con credenciales válidas.
   - login con credenciales inválidas.
   - redirección a dashboard.
   - mantenimiento de sesión.
2. Módulo de usuarios
   - crear usuario con datos válidos.
   - editar usuario existente.
   - eliminar usuario.
   - ver lista de usuarios.
3. Módulo de publicaciones
   - crear publicación con datos completos.
   - editar publicación.
   - eliminar publicación.
   - listar publicaciones.
4. Módulo de categorías de publicaciones
   - crear categoría.
   - editar categoría.
   - eliminar categoría.
   - listar categorías.
5. Módulo de comentarios
   - insertar comentario.
   - editar comentario.
   - eliminar comentario.
   - consultar comentarios por publicación.
6. Módulo de sectores y municipalidades
   - crear sector.
   - editar sector.
   - eliminar sector.
   - listar sectores.
7. Integración UI / backend
   - que cada vista cargue datos del controlador.
   - que las acciones POST y GET respondan correctamente.
   - que no se ejecute SQL directo desde la vista.
8. Pruebas de seguridad básicas
   - validación de entrada de formularios.
   - evitar inyección SQL en consultas.
   - escapar contenido en HTML.

## Recomendaciones de documentación

- Mantener esta hoja en `doc/procesos.md` como guía de refactor.
- Documentar cada cambio de refactorización con un breve `issue` o `commit`.
- No modificar código fuera de `doc/`; esta documentación identifica los puntos de mejora sin cambiar la aplicación.

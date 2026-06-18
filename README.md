# Proyecto Smart City

## Descripción

La plataforma de participación ciudadana es un sistema web orientado a mejorar la comunicación entre la municipalidad y su comunidad. El sistema permitirá publicar información municipal, recibir reportes ciudadanos, gestionar consultas o votaciones, visualizar emprendimientos y comercio local, además de analizar la participación ciudadana mediante herramientas administrativas.

## Arquitectura

El proyecto sigue una arquitectura MVC:

* Models: acceso a datos.
* Views: interfaz gráfica.
* Controllers: lógica de aplicación.

## Tecnologías

* HTML5
* CSS3
* JavaScript (Vanilla JS)
* PHP 8.2 (sin framework)
* MySQL 8
* Apache
* Composer
* PHPUnit

## Requisitos

* PHP >= 8.2
* Composer >= 2.8
* MySQL >= 8.0
* Apache 2.4

## Configuración

Copiar el archivo de ejemplo:

```sh
cp .env.example .env
```

Configurar las variables necesarias:

```env
DB_HOST=localhost
DB_NAME=smartcity
DB_USER=root
DB_PASS=
```

## Instalación

### Dependencias

Gestor de paquetes: Composer

* phpdotenv: gestión de los archivos `.env` y `.env.example`.
* validation: validación de formularios.
* phpunit: implementación de pruebas.

## Instalación vía local

### Ejecución con XAMPP y Apache

Instalar XAMPP y verificar la versión de PHP.

* Iniciar Apache y MySQL.

Instalar las dependencias mediante Composer:

```sh
composer install
```

Acceder a:

```txt
http://localhost/Tis1-proyecto-smart-city/
```

### Alternativa: ejecución mediante el servidor integrado de PHP

```sh
php -S localhost:8000 -t public
```

Acceder a:

```txt
http://localhost:8000
```

### Base de datos MySQL

1. Crear una base de datos llamada `smartcity`.
2. Editar archivo smartcity.sql
```sql
-- comentar esta linea dentro del sql
SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '70a3f71e-6864-11f1-a2f5-244bfee1da32:1-87';
```
3. Importar el archivo `smartcity.sql`.
4. Configurar las credenciales en el archivo `.env`.


## Instrucciones de uso

* Ver publicaciones. (Imagen)
* Iniciar sesión o registrar usuario. (Imagen)
* Ingresar al panel de administración. (Imagen)

## Estructura de carpetas

* **config**: archivos de configuración, base de datos y dependencias de Composer.
* **controllers**: controladores del proyecto.
* **core**: código núcleo de gestión interna del proyecto, por ejemplo: router, database, request y response.
* **Exceptions**: manejo de excepciones base del proyecto.
* **models**: modelos encargados principalmente de la gestión de consultas a la base de datos.
* **views**: vistas visuales de los módulos del proyecto.
* **tests**: realización de pruebas unitarias y de integración.
* **src**: recursos visuales de los módulos del proyecto. Por el momento, `views` y `src` contienen elementos similares.
* **public**: archivos públicos para producción, assets, `.htaccess` e `index.php`.
* **doc**: documentación del proyecto, arquitectura, dependencias y flujo de trabajo.

## Equipo de desarrolladores

* Oscar Gonzalez
* Felipe Alarcon
* Benjamin Cisternas
* Hector Contreras
* Matias Altamirano

## Contribuciones


* Oscar Gonzalez: 
    * mantenedor de publicaciones
* Felipe Alarcon
    * mantenedores de reacciones
* Benjamin Cisternas
    * mantenedor area_municipal
    * panel de admin
    * asignacion de roles
* Hector Contreras
    * mentanedor de los comentarios
* Matias Altamirano
    * login y cierre de sesión
    * mantenedores de admin, sector, usuarios, rubro

## Licencia

MIT License

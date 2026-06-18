# Proyecto Smart City

## Descripcion:
La plataforma de participación ciudadana es un sistema web orientado a mejorar la comunicación entre la municipalidad y su comunidad. El sistema permitirá publicar información municipal, recibir reportes ciudadanos, gestionar consultas o votaciones, visualizar emprendimientos, comercio local y analizar la participación ciudadana mediante herramientas administrativas.

## Arquitectura
El proyecto sigue una arquitectura MVC:
- Models: acceso a datos
- Views: interfaz gráfica
- Controllers: lógica de aplicación

## Tecnologías
- html5,css3, JavaScript (Vanilla JS)
- PHP 8.2 (sin framework)
- MySQL 8
- Apache
- Composer
- PHPUnit

## Requisitos

- PHP >= 8.2
- Composer >= 2.8
- MySQL >= 8.0
- Apache 2.4


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


## Instalacion:
Dependencias:
- gestor de paquetes, composer
    * phpdotenv: gestionar los archivos .env y .env.example
    * validation: validacion de formularios
    * phpunit: implementacion de tests

## Instalacion via local:
### Ejecucion Xampp y apache:
Instalacion de xampp y version de php:  
- iniciar Apache y MySql
Instalacion de dependencias usando composer:
```sh
composer install
```

Acceder a:

```txt
http://localhost/Tis1-proyecto-smart-city/
```

### Ejecucion Servidor php:
```sh
php -S localhost:8000 -t public
#Acceder a:
http://localhost:8000
```

### Base de Datos mysql
1. Crear una base de datos llamada `smartcity`.
2. Importar el archivo `smartcity.sql`.
3. Configurar las credenciales en el archivo `.env`.

## Instrucciones de uso:
- ver publicaciones (Imagen)
- iniciar sesion o registrar usuario (Imagen)
- ingresar panel de administracion (Imagen)



## Estructuras de Carpetas con imagenes
config: archivos de configuracion, base de datos, dependencias de composer.
controllers: Controladores del proyecto
core: codigo nucleo de gestion interna del proyecto ej: (router, database, request, response) en caso de api
Exceptions: manejo de excepciones base del proyecto
models: modelos, principalmente gestion consulta a la base de datos
views: vista visual de los modulos del proyecto
tests: realizaciin de pruebas unitarias y de integracion
src: vista visual de los modulos del proyecto (por el momento views y src son similares).
public: archivos publicos para produccion, assets, .htaccess y index.php
doc: Documentacion del proyecto, arquitectura, dependencias y flujo de proyecto

## Equipo de desarrolladores:
- Oscar
- Felipe
- Benjamin
- Hector
- Matias

## Licence:
MIT License
<?php
require_once __DIR__ . '/config/database.php';
$db = getDatabase();

$db->execute("SET FOREIGN_KEY_CHECKS = 0;");
$db->execute("INSERT IGNORE INTO rol (id_rol, nombre_rol) VALUES (1, 'Admin'), (2, 'Ciudadano')");
$db->execute("INSERT IGNORE INTO municipalidad (id_municipalidad, nombre, comuna, region, direccion, telefono) VALUES (1, 'Smart City', 'Centro', 'Region', 'Dir 123', 5551234)");
$db->execute("INSERT IGNORE INTO sector (id_sector, nombre, id_municipalidad) VALUES (1, 'Centro', 1)");
$db->execute("INSERT IGNORE INTO area_municipal (id_area, nombre_area, descripcion, id_municipalidad) VALUES (1, 'Participacion', 'Area de participacion', 1)");
$db->execute("INSERT IGNORE INTO usuario (rut, nombre, apellido, correo, direccion, contrasenha, id_rol, id_sector) VALUES (12345678, 'Admin', 'Admin', 'admin@admin.com', 'Calle 1', '1234', 1, 1)");
$db->execute("INSERT IGNORE INTO usuario (rut, nombre, apellido, correo, direccion, contrasenha, id_rol, id_sector) VALUES (87654321, 'Ciudadano', 'Comun', 'user@user.com', 'Calle 2', '1234', 2, 1)");
$db->execute("INSERT IGNORE INTO funcionario_municipal (id_funcionario, rut_usuario, id_area_municipal) VALUES (1, 12345678, 1)");
$db->execute("SET FOREIGN_KEY_CHECKS = 1;");

echo "Datos listos!";

# Simple Hashing

Aseguramiento de archivos digitales mediante un sellado de tiempo independiente de una autoridad certificadora

## Requerimientos:

+ PHP 7
	+ php-mcrypt 
	+ php-mysql
+ MySQL 5+
+ MySQL PDO for PHP
+ Apache 2

## Instalación:

1) Extraer los archivos a la carpeta de Apache "HTML Docs".
2) Importar la base de datos "hash" y sus tablas correspondientes: "Build SQL Structure.sql".
3) Crear un usuario administrador en la tabla de usuarios.  
Nota: Las contraseñas deben estar en el hash SHA2-256.  
Por ejemplo la contraseña "12345" en sha256 es:  
`5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5`
```sql
INSERT INTO 'hash'.'user' ('firstname', 'lastname', 'social_id', 'email', 'password', 'creation_date', 'is_active', 'role', 'admin_max_users') VALUES ('Charles', 'Xavier', '0954568745', 'admin@mycompany.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2016-04-06', 'yes', 'admin', '100');
```
3) Editar el archivo "model/dbcore.php" y cambiar la dirección IP del servidor, el usuario y la contraseña.

## Licencia
MIT

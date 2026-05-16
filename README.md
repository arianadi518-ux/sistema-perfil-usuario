# Sistema de Perfil de Usuario y Cambio de Contrasena con PHP y MySQL

## Descripcion

Este proyecto es una aplicacion web desarrollada con PHP y MySQL para gestionar un flujo basico de usuarios. Permite registrar una cuenta, iniciar sesion, acceder a un perfil privado, actualizar datos personales, cambiar la contrasena y cerrar sesion.

El sistema usa PDO para conectarse a la base de datos, sesiones de PHP para controlar el acceso y funciones nativas como `password_hash` y `password_verify` para manejar contrasenas de forma segura.

## Funcionalidades

- Registro de usuario con cedula, nombre, correo y contrasena.
- Validacion de campos obligatorios.
- Validacion de formato de correo.
- Validacion de correo o cedula duplicados.
- Inicio de sesion con correo y contrasena.
- Proteccion de paginas privadas mediante sesion.
- Visualizacion de datos del perfil.
- Actualizacion de nombre y correo.
- Cambio de contrasena verificando la contrasena actual.
- Cierre de sesion.

## Requisitos

- PHP 8 o superior.
- MySQL o MariaDB.
- Servidor local como XAMPP, WAMP, Laragon o MAMP.
- Navegador web.
- phpMyAdmin o consola MySQL.

## Como poner a correr la aplicacion

### Opcion 1: Ejecutar con XAMPP, WAMP, Laragon o MAMP

1. Copia la carpeta del proyecto dentro del directorio publico del servidor local.

   Ejemplo generico:

   ```txt
   ruta/del/servidor/proyecto
   ```

2. Inicia los servicios de Apache y MySQL desde el panel del servidor local.

3. Abre phpMyAdmin en el navegador.

   Ejemplo:

   ```txt
   http://localhost/phpmyadmin
   ```

4. Importa el archivo `database.sql`.

   Este archivo crea la base de datos `perfil_usuario` y la tabla `usuarios`.

5. Revisa el archivo `config/database.php` y confirma que los datos coincidan con tu entorno local.

   Configuracion por defecto:

   ```php
   $host = "localhost";
   $dbname = "perfil_usuario";
   $user = "root";
   $password = "";
   ```

6. Abre la aplicacion en el navegador.

   Ejemplo generico:

   ```txt
   http://localhost/nombre-del-proyecto/
   ```

7. Si todo esta correcto, el sistema redirige al flujo de inicio de sesion o registro.

### Opcion 2: Ejecutar con el servidor integrado de PHP

Esta opcion sirve si ya tienes PHP instalado y MySQL en ejecucion.

1. Abre una terminal dentro de la carpeta del proyecto.

2. Ejecuta:

   ```bash
   php -S localhost:8000
   ```

3. Abre en el navegador:

   ```txt
   http://localhost:8000
   ```

4. Asegurate de haber importado antes `database.sql` en MySQL y de que `config/database.php` tenga los datos correctos de conexion.

## Estructura del proyecto

```txt
proyecto/
|-- assets/
|   `-- css/
|       `-- style.css
|
|-- config/
|   `-- database.php
|
|-- includes/
|   |-- auth.php
|   |-- header.php
|   `-- footer.php
|
|-- cambiar_password.php
|-- database.sql
|-- guion_video.txt
|-- index.php
|-- login.php
|-- logout.php
|-- package-lock.json
|-- package.json
|-- perfil.php
|-- README.md
|-- register.php
|-- vite.config.js
```

## Explicacion de archivos y carpetas

### `database.sql`

Contiene el script SQL para crear la base de datos `perfil_usuario` y la tabla `usuarios`.

La tabla guarda:

- `id`: identificador principal del usuario.
- `cedula`: documento o identificador del usuario.
- `nombre`: nombre del usuario.
- `correo`: correo electronico usado para iniciar sesion.
- `password`: contrasena cifrada con `password_hash`.
- `fecha_registro`: fecha automatica en la que se crea el usuario.

### `config/database.php`

Contiene la conexion a MySQL usando PDO.

Aqui se configuran:

- Servidor de base de datos.
- Nombre de la base de datos.
- Usuario.
- Contrasena.
- Opciones de PDO.

Este archivo es necesario para que `register.php`, `login.php`, `perfil.php` y `cambiar_password.php` puedan consultar o modificar informacion en la base de datos.

### `includes/auth.php`

Centraliza funciones relacionadas con autenticacion y seguridad.

Incluye:

- Inicio de sesion con `session_start`.
- Funcion `e()` para escapar datos con `htmlspecialchars`.
- Funcion `require_login()` para proteger paginas privadas.
- Funcion `redirect_if_logged()` para evitar que un usuario autenticado vuelva al login o registro.

### `includes/header.php`

Contiene la parte inicial del HTML, enlaza el archivo de estilos y muestra el menu de navegacion.

El menu cambia segun el estado del usuario:

- Si no ha iniciado sesion, muestra Login y Registro.
- Si ya inicio sesion, muestra Perfil, Cambiar contrasena y Cerrar sesion.

### `includes/footer.php`

Cierra la estructura principal del HTML.

Se usa junto con `header.php` para evitar repetir el mismo codigo en varias paginas privadas.

### `index.php`

Es el punto de entrada principal del proyecto.

Sirve para enviar al usuario hacia el flujo correspondiente de la aplicacion.

### `register.php`

Maneja el registro de nuevos usuarios.

En este archivo se validan los datos del formulario, se verifica que no exista otro usuario con el mismo correo o cedula, se cifra la contrasena con `password_hash` y se inserta el usuario en la tabla `usuarios`.

Despues del registro correcto, se crea la sesion del usuario y se redirige al perfil.

### `login.php`

Maneja el inicio de sesion.

El sistema recibe el correo y la contrasena, busca el usuario en la base de datos y verifica la contrasena con `password_verify`. Si los datos son correctos, guarda la informacion principal en `$_SESSION` y redirige a `perfil.php`.

### `perfil.php`

Es una pagina privada, por eso usa `require_login()`.

Muestra los datos del usuario autenticado:

- Cedula.
- Nombre.
- Correo.
- Fecha de registro.

Tambien permite actualizar nombre y correo. Antes de guardar los cambios, valida que los campos no esten vacios, que el correo tenga formato correcto y que no pertenezca a otro usuario.

### `cambiar_password.php`

Permite cambiar la contrasena del usuario autenticado.

Primero solicita:

- Contrasena actual.
- Nueva contrasena.
- Confirmacion de la nueva contrasena.

Luego verifica que la contrasena actual sea correcta con `password_verify`. Si es correcta, cifra la nueva contrasena con `password_hash` y actualiza el registro en la base de datos.

### `logout.php`

Cierra la sesion del usuario.

Limpia `$_SESSION`, elimina la cookie de sesion cuando corresponde, destruye la sesion con `session_destroy` y redirige al login.

### `assets/css/style.css`

Contiene los estilos compilados que usa la aplicacion.

Este archivo define la apariencia visual de formularios, botones, contenedores y mensajes.

### `package.json`, `package-lock.json` y `vite.config.js`

Estos archivos pertenecen a la configuracion de herramientas frontend.

La aplicacion PHP puede ejecutarse directamente con Apache o con el servidor integrado de PHP. Solo necesitas usar estos archivos si vas a modificar o recompilar estilos con herramientas de Node.

## Flujo de prueba recomendado

1. Importar `database.sql` en MySQL.
2. Revisar `config/database.php`.
3. Abrir la aplicacion en el navegador.
4. Entrar a `register.php` y registrar un usuario nuevo.
5. Confirmar que el sistema redirige a `perfil.php`.
6. Actualizar el nombre o correo desde el perfil.
7. Entrar a `cambiar_password.php`.
8. Cambiar la contrasena.
9. Cerrar sesion desde `logout.php`.
10. Iniciar sesion nuevamente desde `login.php` usando la nueva contrasena.

## Seguridad aplicada

- Las contrasenas no se guardan en texto plano.
- Se usa `password_hash` para cifrar contrasenas.
- Se usa `password_verify` para validar contrasenas.
- Las consultas a la base de datos usan PDO y sentencias preparadas.
- Las paginas privadas verifican que exista una sesion activa.
- Se usa `session_regenerate_id(true)` al registrar o iniciar sesion correctamente.
- Se validan campos obligatorios y formato de correo.
- Se escapan datos mostrados con `htmlspecialchars`.

## Notas

- Si aparece un error de conexion, revisa `config/database.php`.
- Si la tabla no existe, vuelve a importar `database.sql`.
- Si el login falla despues de cambiar la contrasena, verifica que estes usando la contrasena nueva.
- Si cambias el nombre de la carpeta del proyecto, tambien cambia la URL que usas en el navegador.

<H1 align="center">GREEN FLAME | LARAVEL / PHP / MySQL</p>



# Set-up
### El proyecto fue realizado en __PHP 8.2.6__ y __Laravel 10.10__


## __1- Clonar el reposiotorio__

Debe situarce en la carpeta donde desea clonar el repositorio.
Luego ejecutar el siguiente comando desde la terminal:

```
git clone https://github.com/scottimariano/greenFlameApp.git
```

## __2- Installar dependencias__

Una vez que el repositorio fue clonado y se crearon las carpetas del proyecto, situarse en la carpeta raiz del mismo y ejecutar desde la terminal:
```
composer install
```
Esto instalará todas las dependencias necesesarias para el correcto funcionamiento del proyecto.

## __3- Archivos de entorno__
En el repositorio, se encuentra un arhivo .env.example de ejemplo, con las configuraciones basicas.
Con el siguiente comando, creará un archivo .env con esa base, y allí podrá configurar sus opciones segun sea necesario.
```
cp .env.local .env
```

Una vez creado el archivo, con el siguiente comando estaremos generando la Appication Key que utiliza laravel.
```
php artisan key:generate
```
La misma se alamcenará automaticamente.

## __4-Base de datos__

Desde la consola MySQL (en general => user: root, pass: root) deberá crear la nueva base de datos.
```
mysql -u root -p

CREATE DATABASE greenFlame;

exit
```

En el caso de desear conectarse desde un cliente (DBeaver, Sequel Pro, etc) en la maquina host usar la siguiente configuración (por defecto):

```
HOST: 127.0.0.1
USERNAME: root
PASSWORD: root
PORT: 3306
```
### __Migraciones y seeder__
Ya creamos la base de datos en el paso anterior. Ahora debemos crear las tablas y cargas los datos iniciales para poder trabajar.
```
php artisan migrate:fresh --seed
```

## __5-Iniciar el servicio__
En este punto, ya estariamos listos para poder levantar nuestro servidor.
el mismo por defecto inicia en el puerto 8000 de nuestro localhost, pero en caso de estar en uso, la aplicación nos comunicara el puerto correcto.
```
php artisan serve
```


La aplicacion fue desarrollada como una API, capaz de manejar todas las operaciones indicadas en los requerimientos.
Debajo se encuentra el listado de rutas disponibles y su detalle.
En el repositorio, encotrará un carpeta llamada __postman__, que contiene una coleccion de *requests* para probar la aplicación.

__Datos para el login__
```
user: admin@example.com
pass: password
```


| METODO | RUTA | DESCRIPCIÓN | OBVS
|---|---|---|---|
| POST | /login | autenticación del usuario | datos según lo indicado en requerimientos
| GET | /logout | cierre de sesión del usuario | 
| GET | /discount | listado de descuentos | __parametros:__ page, rentadora, region, nombre, AWD/BCD
| GET | /discount/__id__ | detalle de descuento por id | 
| POST | /discount | creación de un descuento | los datos deben ser enviado por body
| PUT | /discount/__id__ | edición de un descuento | los datos deben ser enviado por body
| DELETE | /discount/__id__ | eliminación de un descuento | se realiza soft delete
| PUT | /discount/__id__/restore | recuperación de un descuento eliminado | 
| PUT | /discount/download | descarga de archivo con listado de descuentos | descuentos.csv


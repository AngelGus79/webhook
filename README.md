Despliegue automatizado usando webhooks
===================

En este documento se describe una manera automatica de realizar despliegues, usando git y webhooks de github. 

Pre - requisitos
-------------

 - Un servidor web con git y php instalado. 
 - Una pc de desarrollo con git instalado
 - Una cuenta en github
 
para esta prueba se utilizó:
 
 Servidor web virtualizado en VMware 11.0
 - Ubuntu 15.10 
 - Apache 2.4.12
 - git 2.5.0
 - php 5.6.11
 
Una Pc de desarrollo:
 - Windows 10
 - git 2.5.0
   

Objetivo
-------------

El objetivo de este documento es la configuración del entorno de trabajo por lo que se omitira la instalacion de las aplicaciones usadas.


Configuración del servidor web
-------------

1.- Accedemos a la ruta en donde va a estar alojado nuestro proyecto en produccion en nuestro ejemplo var/www/html. Es importante mencionar que todo esto se debe hacer con un tipo de usuario normal, en caso de que no se puedan realizar modificaiones habra que otorgarle la autorizacion necesaria.

2.- Inicializamos un nuevo repositorio

```
$ git init
```

3.- En caso de no haber configurado nuestros datos en git, tendremos que hacerlo

```
$ git config user.name *nombre del usuario*
$ git config user.email *correo electrónico*
```

4.- Creamos un archivo php para usarlo de prueba en nuestro ejemplo lo llamamos index.php.

```sh
<html>
<head></head>
<body>
<?php
  echo "¡Hola Mundo!";
?>
</body>
</html>
```

5.- Creamos el archivo github.php que es el que nos ayudara a realizar la actualizacion.

```
<?php
  $out = shell_exec("/usr/bin/git pull 2>&1");
  var_dump($out);
?>
```

El Webhook es una URL que se habilita en la ocurrencia de un evento mediante un HTTP POST, nosotros configuramos esta URL y en cuál evento se debe habilitar. En nuestro ejemplo nuestro webhook lo vamos a configurar cada que hagan un `git push` al repositorio en github y posteriormente de manera automatica se enviara el Webhook mediante un HTTP POST a nuestro servidor el cual hara que se ejecute el archivo github.php. Este no es mas que un `git pull` y el estado final de la ejecucion del `git pull`.

6.- Una vez creado un repositorio en github (No agreguen el archivo README.md), se procede a cargar estos archivos.

```
$ git add -A
$ git commit -m "Carga de archivos en repositorio github"
$ git remote add origin LA_URL_DEL REPOSITORIO
$ git push origin master
$ git push --set-upstream origin master
```


Configuración del webhook
-------------

1.- Accedemos al repositorio de github y hacemos click en la opcion settings > webhooks 
y hacemos click en en boton < add webhook >

2.- En el campo Payload URL, debemos escribir la url de nuestro servidor apuntando a la pagina github.php ejemplo: http://miUrl/github.php, en el campo Content type, debemos seleccionar la opcion application/x-www-form-urlencoded, nos pregunta tambien sobre el evento en donde nos gustaria desencanedar el webook, debemos seleccionar just the push event, dejamos activado el webhook y finalmente hacemos click en el boton < add webhook >.

Configuración de la(s) Pc(s) de desarrollo
-------------

Clonamos el repositorio remoto en la ubicacion deseada. Para esto seguimos los pasos 2 y 3 de la instalacion del servidor y continuamos con lo siguiente.
```
$ git clone URL_DEL_REPOSITORIO_REMOTO
```

Una vez realizado cambios en el proyecto.
```
$ git add -A
$ git commit -m "Actualizacion del proyecto"
$ git remote add origin URL_DEL_REPOSITORIO_REMOTO
$ git push origin master
```

En este momento el cambio automaticamente debe verse reflejado en el servidor de produccion, con las ventajas sobre los servidores ftp de llevar un control de versiones en donde pueden trabajar varios desarrolladores.


Referencias
-------------

http://www.ubuntu-es.org/node/6304#.V_xCnCQnpQg

https://www.youtube.com/watch?v=9L89EjZuy7o

https://git-scm.com/documentation





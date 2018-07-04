Pasos para instalar el proyecto.

1. Una vez descargado el proyecto a través del comando "git clone", se debe acceder a la carpeta del proyecto desde el CMD y correr el comando "composer install" para instalar todos los paquetes de composer.

2. Correr la siguiente query en MySql 5.7
    CREATE DATABASE linea29;
    GRANT USAGE ON *.* TO admin@localhost IDENTIFIED BY 'admin';
    GRANT ALL PRIVILEGES ON linea29.* TO admin@localhost ;
    
3. Configurar el archivo .env en función del archivo .env.example.; configurar los siguientes campos con la siguiente información:
    DB_DATABASE=linea29
    DB_USERNAME=admin
    DB_PASSWORD=admin    

4. Correr el comando "php artisan migrate" para crear las tablas de SQL.

5. Retomando el paso número 1, desde el mismo CMD correr el comando "php artisan key:generate"

6. En la carpeta sql_inserts se cuentan con comandos de SQL (INSERTS) que se deben correr en la consola de MySql para tener una base de branches y stops (así no se empieza con la carga de 0).

7. Una vez realizado este último paso, se puede levantar el servidor con el comando "php artisan serve"

8. Para poder acceder a todas las funcionalidades del proyecto es IMPORTANTE crear un usuario desde la opción Register.

<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra
    Fecha: 29/11/2021
-->
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>IMG - LoginLogoffTema5 - Carga inicial DB</title>
    </head>
    <body>
        <h1>Script de carga inicial de tablas</h1>
        <?php
        /**
         * @author Isabel Martínez Guerra
         * @since 28/11/2021
         * 
         * Fichero de inserción en las tablas del proyecto Login Logoff Tema 5.
         */
        
        require_once '../config/configDB.php'; // Fichero de configuración de la base de datos.

        $sInstrucciones = <<<QUERY
            /* Inserción en tablas */
            USE dbs4868794;
                
            INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio) VALUES
                ('INF','Departamento de Informatica', UNIX_TIMESTAMP(),1.5),
                ('BIO','Departamento de Biologia', UNIX_TIMESTAMP(),2.5),
                ('ING','Departamento de Inglés', UNIX_TIMESTAMP(),3.5),
                ('LEN','Departamento de Lengua', UNIX_TIMESTAMP(),4.5),
                ('MUS','Departamento de Musica', UNIX_TIMESTAMP(),1.5);
        QUERY;

        try {
            // Conexión con la base de datos.
            $oDB = new PDO(HOST, USER, PASSWORD);

            // Mostrado de las excepciones.
            $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ejecución de la inserción del contenido de las tablas.
            $oDB->exec($sInstrucciones);

            echo '<div>Carga inicial de la tabla realizada con éxito.</div>';
        } catch (PDOException $exception) {
            /*
             * Mostrado del código de error y su mensaje.
             */
            echo '<div>Se han encontrado errores:</div><ul>';
            echo '<li>' . $exception->getCode() . ' : ' . $exception->getMessage() . '</li>';
            echo '</ul>';
        } finally {
            unset($oDB);
        }
        ?>
    </body>
</html>
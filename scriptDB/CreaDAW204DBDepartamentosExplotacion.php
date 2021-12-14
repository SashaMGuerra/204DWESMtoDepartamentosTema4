<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra
    Fecha: 29/11/2021
-->
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>IMG - LoginLogoffTema5 - Creación DB</title>
    </head>
    <body>
        <h1>Script de creación de tablas</h1>
        <?php
        /**
         * @author Isabel Martínez Guerra
         * @since 28/11/2021
         * 
         * Fichero de creación de las tablas del proyecto Login Logoff Tema 5.
         */
        
        require_once '../config/configDB.php'; // Fichero de configuración de la base de datos.

        /**
         * Creación de las tablas.
         */
        $sInstrucciones = <<<QUERY
            /* Uso de la base de datos */
            USE dbs4868794;

            CREATE TABLE IF NOT EXISTS T02_Departamento(
                T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
                T02_DescDepartamento VARCHAR(255) NOT NULL,
                T02_FechaCreacionDepartamento INT NOT NULL,
                T02_VolumenDeNegocio FLOAT NOT NULL,
                T02_FechaBajaDepartamento DATETIME NULL
            ) ENGINE=INNODB;
        QUERY;

        try {
            // Conexión con la base de datos.
            $oDB = new PDO(HOST, USER, PASSWORD);

            // Mostrado de las excepciones.
            $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Ejecución de la creación de las tablas.
            $oDB->exec($sInstrucciones);
            
            echo '<div>Query realizado.</div>';
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

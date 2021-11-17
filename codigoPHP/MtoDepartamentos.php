<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra.
    Fecha: 16/11/2021
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link href="../webroot/style/mtoDepartamentos.css" rel="stylesheet" type="text/css"/>
        <title>Mantenimiento departamentos - Página principal</title>
    </head>
    <body>
        <?php
        /**
         * @author Sasha
         * @since 16/11/2021
         * Fecha de modificación: 17/11/2021
         * 
         * Ventana principal de la aplicación.
         */
        ?>
        <header>
            <h1>Mantenimiento Departamentos</h1>
        </header>

        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <fieldset>
                <legend>Búsqueda de departamentos</legend>
                <table>
                    <tr>
                        <td><label for="descDepartamento">Departamento a buscar</label></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="descDepartamento" id="descDepartamento" value="<?php echo $_REQUEST['descDepartamento'] ?? '' ?>"></td>
                    </tr>
                    <tr>
                        <td><?php echo '<span>' . $aErrores['descDepartamento'] . '</span>' ?></td>
                    </tr>
                </table>
            </fieldset>
            <input type="submit" name="submit" id="submit">
        </form>

    </body>
</html>

<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra.
    Fecha: 16/11/2021
-->
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link href="../webroot/style/mtoDepartamentos.css" rel="stylesheet" type="text/css"/>
        <title>Mantenimiento departamentos - Página principal</title>
        <style>
            form{
                margin-top: 10px;
            }
            fieldset.busqueda{
                text-align: center;
                border: 2px solid midnightblue;
            }
            fieldset.busqueda legend{
                padding: 3px 10px;
                text-align: left;
                font-size: small;
                color: midnightblue;
                background-color: lavender;
            }
            fieldset.envio{
                border: none;
                text-align: right;
            }
            input[type='text']{
                background-color: lavender;
            }
            
            table.departamentos{
                border-top: 2px solid firebrick;
                border-collapse: collapse;
            }
            caption{
                padding-bottom: 5px;
                text-transform: uppercase;
                font-weight: bold;
            }
            table.departamentos td{
                padding: 0 20px;
            }
            table.departamentos tr:nth-child(even) td, table.departamentos tr:nth-child(even) th{
                background-color: mistyrose;
            }
            table.departamentos tr:first-child th{
                padding: 5px 0;
                border-bottom: 1px solid firebrick;
            }
            img{
                width: 30px;
            }
        </style>
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
            <a class="volver" href="../index.php">Volver</a>
            <h1>Mantenimiento Departamentos</h1>
        </header>
        <main>
            <?php
            //Librería de validación.
            include '../core/210322ValidacionFormularios.php';

            // Constantes para la conexión con la base de datos.
            require_once '../config/configDB.php';

            // Constantes para el parámetro "obligatorio".
            require_once '../config/constValidacion.php';

            /*
             * Inicialización del array de elementos del formulario.
             */
            $aFormulario = [
                'descDepartamento' => ''
            ];

            /*
             * Inicialización del array de errores.
             */
            $aErrores = [
                'descDepartamento' => ''
            ];
            
            /*
             * Si el formulario ha sido enviado, valida el campo y registra los errores.
             */
            if (isset($_REQUEST['submit'])) {
                /*
                 * Manejador de errores. Por defecto asume que no hay ningún
                 * error (true). Si encuentra alguno se pone a false.
                 */
                $bEntradaOK = true;

                /*
                 * Registro de errores. Valida todos los campos.
                 */
                $aErrores['descDepartamento'] = validacionFormularios::comprobarAlfanumerico($_REQUEST['descDepartamento'], 255, 1, OPCIONAL);

                /*
                 * Recorrido del array de errores.
                 * Si existe alguno, cambia el manejador de errores a false
                 * y limpia el campo en el $_REQUEST.
                 */
                foreach ($aErrores as $sCampo => $sError) {
                    if ($sError != null) {
                        $_REQUEST[$sCampo] = ''; //Limpieza del campo.
                        $bEntradaOK = false;
                    }
                }
            }

            /*
             * Si el formulario no ha sido enviado, pone el manejador de errores
             * a false para que no entre en el if tras envío correcto.
             */
            else {
                $bEntradaOK = false;
            }

            /*
             * Si el formulario ha sido enviado y no ha tenido errores
             * muestra la información enviada.
             */
            if ($bEntradaOK) {
                /*
                 * Recogida de la información enviada.
                 */
                $aFormulario['descDepartamento'] = $_REQUEST['descDepartamento'];
            }

            /*
             * Selección desde la base de datos.
             */
            try {
                // Conexión con la base de datos.
                $oDB = new PDO(HOST, USER, PASSWORD);

                // Mostrado de las excepciones.
                $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Query de búsqueda.
                $sConsulta = <<<QUERY
                    SELECT * FROM Departamento WHERE descDepartamento LIKE '%{$aFormulario['descDepartamento']}%';
                QUERY;

                /*
                 * Ejecución de la consulta preparada.
                 */
                $oResultadoConsulta = $oDB->prepare($sConsulta);
                $oResultadoConsulta->execute();

                /*
                 * Recogida de la información del select.
                 */
                $aDepartamentos = $oResultadoConsulta->fetchAll();

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
            
            /*
             * Formulario de búsqueda. Siempre se muestra, se haya enviado
             * o no la información.
             * Su único input es obligatorio.
             * Si tiene algún error, lo muestra debajo del input.
             */
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <fieldset class="busqueda">
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
                <fieldset class="envio">
                    <input type="submit" name="submit" id="submit">
                </fieldset>
            </form>
            <table class="departamentos">
                <caption>Departamentos</caption>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Fecha de baja</th>
                    <th>Volumen de negocio</th>
                    <th colspan="3">
                </tr>
                <?php
                    /*
                     * Mostrado de la información del select.
                     * Al abrirse la aplicación, muestra todos los departamentos.
                     */
                    foreach ($aDepartamentos as $aDepartamento) {
                        echo '<tr>';
                        echo "<td>".$aDepartamento['codDepartamento']."</td>";
                        echo "<td>".$aDepartamento['descDepartamento']."</td>";
                        echo "<td>".$aDepartamento['fechaBaja']."</td>";
                        echo "<td>".$aDepartamento['volumenNegocio']."</td>";
                        ?>
                        <th><a href="modificar.php"><img src="../webroot/media/img/modify.png" alt="modificar"/></a></th>
                        <th><img src="../webroot/media/img/delete.png" alt="eliminar"/></th>
                        <th><img src="../webroot/media/img/view.png" alt="ver"/></th>
                        </tr>
                        <?php
                    }
                ?>
            </table>
        </main>



    </body>
</html>

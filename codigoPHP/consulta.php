<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra
    Fecha: 16/11/2021
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>IMG - DWES 4-4 PDO</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <main>
            <?php
            /*
             * @since 16/10/2021
             * Fecha de última modificación: 16/10/2021
             * @version 1.0
             * @author Sasha
             * 
             * Búsqueda y mostrado de departamentos según descripción.
             */

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
             * Formulario de búsqueda. Siempre se muestra, se haya enviado
             * o no la información.
             * Su único input es obligatorio.
             * Si tiene algún error, lo muestra debajo del input.
             */
            ?>
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

            <?php
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
             * 
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

                /*
                 * Inserción en la base de datos.
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
                    
                    echo '<h2>Departamentos encontrados: </h2>';
                    /*
                     * Mostrado del select.
                     */
                    $aDepartamento = $oResultadoConsulta->fetchObject();
                    echo '<table class="showSelect">';
                    while ($aDepartamento) {
                        echo '<tr>';
                        echo "<td>$aDepartamento->codDepartamento</td>";
                        echo "<td>$aDepartamento->descDepartamento</td>";
                        echo "<td>$aDepartamento->fechaBaja</td>";
                        echo "<td>$aDepartamento->volumenNegocio</td>";
                        echo '</tr>';
                        $aDepartamento = $oResultadoConsulta->fetchObject();
                    }
                    echo '</table>';
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
            }
            ?>

        </main>
    </body>
</html>

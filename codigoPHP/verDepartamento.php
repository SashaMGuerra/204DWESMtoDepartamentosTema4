<!DOCTYPE html>
<?php
/**
 * @author Sasha
 * @since 20/11/2021
 * Fecha de modificación: 20/11/2021
 * 
 * Ventana de mostrado de toda la información de un departamento.
 * Dado que no se realizan cambios, sólo se puede aceptar, volviendo a la página
 * anterior.
 */

//Librería de validación.
include '../core/210322ValidacionFormularios.php';

// Constantes para la conexión con la base de datos.
require_once '../config/configDB.php';


$aFormulario = [
    'codDepartamento' => $_REQUEST['codDepartamentoEnCurso'],
    'descDepartamento' => '',
    'fechaBaja' => '',
    'volumenNegocio' => ''
];

/**
 * Si el formulario se ha enviado con aceptar, regresa a la página anterior.
 */
if (isset($_REQUEST['aceptar'])) {
    header('Location: MtoDepartamentos.php');
}
?>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Mantenimiento departamentos - Eliminar departamento</title>
        <link href="../webroot/style/mtoDepartamentos.css" rel="stylesheet" type="text/css"/>
        <style>
            header{
                padding: 15px 0;
            }

            form{
                max-width: 900px;
            }

            fieldset.acciones{
                margin-top: 10px;
                border: 2px solid midnightblue;
            }
            fieldset.acciones legend{
                padding: 3px 10px;
                text-align: left;
                font-size: small;
                color: midnightblue;
                background-color: lavender;
            }

            table{
                table-layout: fixed;
            }
            table tr td:first-child{
                text-align: right;
            }
            table tr td:not(:first-child):not(:last-child){
                text-align: center;
            }
            table tr td:last-child{
                color: red;
                font-size: 12px;
            }

            fieldset.envio{
                text-align: center;
                border: none;
            }

        </style>
    </head>
    <body>
        <header>
            <a class="volver" href="MtoDepartamentos.php">Volver</a>
            <h2>Eliminación de departamento</h2>
        </header>
        <main>
            <?php
            /**
             * Al accederse a la página, se muestra el formulario con la información
             * del departamento cuyo código se ha pasado por la url.
             */
            try {
                $sConsulta = <<<QUERY
                    SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$_REQUEST['codDepartamentoEnCurso']}';
                QUERY;

                // Conexión con la base de datos.
                $oDB = new PDO(HOST, USER, PASSWORD);
                $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Ejecución de la consulta.
                $oConsulta = $oDB->prepare($sConsulta);
                $oConsulta->execute();
                $oResultadoConsulta = $oConsulta->fetch();

                /**
                 * Recogida de los datos del departamento.
                 */
                $aFormulario['codDepartamento'] = $oResultadoConsulta['T02_CodDepartamento'];
                $aFormulario['descDepartamento'] = $oResultadoConsulta['T02_DescDepartamento'];
                $aFormulario['fechaBaja'] = $oResultadoConsulta['T02_FechaBajaDepartamento'];
                $aFormulario['volumenNegocio'] = $oResultadoConsulta['T02_VolumenDeNegocio'];
            
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

            /**
             * Mostrado de la información de los campos.
             * Si fechaBaja no tiene valor, muestra una raya -
             */
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?codDepartamentoEnCurso=" . $aFormulario['codDepartamento'] ?>" method="post">
                <fieldset class="acciones">
                    <legend>Eliminación</legend>
                    <table>
                        <tr>
                            <td><label for="codDepartamento">Código</label></td>
                            <td><input type="text" id="codDepartamento" name="codDepartamento" value="<?php echo $aFormulario['codDepartamento'] ?>" disabled></td>
                        </tr>
                        <tr>
                            <td><label for="descDepartamento">Descripción</label></td>
                            <td><input type="text" id="descDepartamento" name="descDepartamento" value="<?php echo $aFormulario['descDepartamento'] ?>" disabled></td>
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha baja</label></td>
                            <td><input type="text" id="fechaBaja" name="fechaBaja" value="<?php echo $aFormulario['fechaBaja'] ?? '-' ?>" disabled></td>
                        </tr>
                        <tr>
                            <td><label for="volumenNegocio">Volumen negocio</label></td>
                            <td><input type="text" id="volumenNegocio" name="volumenNegocio" value="<?php echo $aFormulario['volumenNegocio'] ?>" disabled></td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset class="envio">
                    <input type="submit" name="aceptar" id="aceptar" value="Aceptar">
                </fieldset>
            </form>
        </main>
    </body>
</html>

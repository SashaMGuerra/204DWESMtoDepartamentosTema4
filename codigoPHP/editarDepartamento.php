<!DOCTYPE html>
<?php
/**
 * @author Sasha
 * @since 17/11/2021
 * Fecha de modificación: 18/11/2021
 * 
 * Ventana de modificación de datos de un departamento.
 */

/**
 * Si el formulario ha sido cancelado, regresa a la página anterior sin realizar
 * ninguna acción.
 */
if(isset($_REQUEST['cancelar'])){
    header('Location: MtoDepartamentos.php');
}

//Librería de validación.
include '../core/210322ValidacionFormularios.php';

// Constantes para la conexión con la base de datos.
require_once '../config/configDB.php';

// Constantes para el parámetro "obligatorio".
require_once '../config/constValidacion.php';

$aFormulario = [
    'codDepartamento' => $_REQUEST['codDepartamentoEnCurso'],
    'descDepartamento' => '',
    'fechaBaja' => '',
    'volumenNegocio' => ''
];
$aErrores = [
    'descDepartamento' => '',
    'volumenNegocio' => ''
];

/**
 * Si el formulario se ha enviado, valida los campos y registra los errores.
 */
if(isset($_REQUEST['aceptar'])){
    // Manejador de errores. Por defecto asume que no hay.
    $bEntradaOK = true;

    // Registro de errores: validación de campos.
    $aErrores['descDepartamento'] = validacionFormularios::comprobarAlfanumerico($_REQUEST['descDepartamento'], 255, 5, OBLIGATORIO);
    $aErrores['volumenNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'], 5000, 0, OBLIGATORIO);

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

/**
 * Si la entrada es correcta, actualiza los campos y retorna a la página anterior.
 */
if ($bEntradaOK) {
    // Recogida de la información.
    $aFormulario['descDepartamento'] = $_REQUEST['descDepartamento'];
    $aFormulario['volumenNegocio'] = $_REQUEST['volumenNegocio'];

    // Modificación de la base de datos.
    try {
        $sConsulta = <<<QUERY
            UPDATE T02_Departamento SET T02_DescDepartamento = :modDescripcion,
            T02_VolumenDeNegocio = :modVolumenNegocio
            WHERE T02_CodDepartamento= '{$aFormulario['codDepartamento']}';
        QUERY;

        // Conexión con la base de datos.
        $oDB = new PDO(HOST, USER, PASSWORD);

        // Mostrado de las excepciones.
        $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ejecución dela consulta.
        $oConsulta = $oDB->prepare($sConsulta);

        $oConsulta->bindParam(':modDescripcion', $aFormulario['descDepartamento']);
        $oConsulta->bindParam(':modVolumenNegocio', $aFormulario['volumenNegocio']);
        
        $oConsulta->execute();

        // Regreso a la página principal
        header('Location: MtoDepartamentos.php');
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
<html>
    <head>
        <meta charset="UTF-8">
        <title>Mantenimiento departamentos - Modificar departamento</title>
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
            input#cancelar{
                margin-right: 15%;
            }
            input#aceptar{
                margin-left: 15%;
            }

            label.obligatorio:after{
                content: '*';
                color: red;
            }
            input.obligatorio{
                background-color: mistyrose;
            }
        </style>
    </head>
    <body>
        <header>
            <a class="volver" href="MtoDepartamentos.php">Volver</a>
            <h2>Modificación de departamento</h2>
        </header>
        <main>
            <?php
            /**
             * Si es la primera vez que se accede a la página, o si se ha enviado
             * el formulario con datos incorectos, se muestra el formulario con
             * la información del departamento cuyo código se ha pasado por la url.
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
             * Por cada input editable, si la variable $_REQUEST no existe o
             * su valor es '' (fue limpiada por existir error), muestra la información
             * que el campo tenía originalmente.
             * Si existe y tiene valor, muestra esa información.
             */
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?codDepartamentoEnCurso=" . $aFormulario['codDepartamento'] ?>" method="post">
                <fieldset class="acciones">
                    <legend>Modificación</legend>
                    <table>
                        <tr>
                            <td><label for="codDepartamento">Código</label></td>
                            <td><input type="text" id="codDepartamento" name="codDepartamento" value="<?php echo $aFormulario['codDepartamento'] ?>" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label class="obligatorio" for="descDepartamento">Descripción</label></td>
                            <td><input class="obligatorio" type="text" id="descDepartamento" name="descDepartamento" value="<?php echo !isset($_REQUEST['descDepartamento']) || $_REQUEST['descDepartamento']==''?$aFormulario['descDepartamento']:$_REQUEST['descDepartamento'];?>"></td>
                            <td><?php echo '<span>' . $aErrores['descDepartamento'] . '</span>' ?></td>
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha baja</label></td>
                            <td><input type="text" id="fechaBaja" name="fechaBaja" value="<?php echo $aFormulario['fechaBaja'] ?? '-' ?>" disabled=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label class="obligatorio" for="volumenNegocio">Volumen negocio</label></td>
                            <td><input class="obligatorio" type="text" id="volumenNegocio" name="volumenNegocio" value="<?php echo !isset($_REQUEST['volumenNegocio']) || $_REQUEST['volumenNegocio']==''?$aFormulario['volumenNegocio']:$_REQUEST['volumenNegocio'];?>"></td>
                            <td><?php echo '<span>' . $aErrores['volumenNegocio'] . '</span>' ?></td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset class="envio">
                    <input type="submit" name="cancelar" id="cancelar" value="Cancelar">
                    <input type="submit" name="aceptar" id="aceptar" value="Aceptar">
                </fieldset>
            </form>
        </main>
    </body>
</html>

<?php
/**
 * @author Sasha
 * @since 20/11/2021
 * Fecha de modificación: 20/11/2021
 * 
 * Ventana de creación de un departamento.
 */

/**
 * Si el formulario ha sido cancelado, regresa a la página anterior sin realizar
 * ninguna acción.
 */
if(isset($_REQUEST['cancelar'])){
    header('Location: MtoDepartamentos.php');
    exit;
}

//Librería de validación.
include '../core/210322ValidacionFormularios.php';

// Constantes para la conexión con la base de datos.
require_once '../config/configDB.php';

// Constantes para el parámetro "obligatorio".
require_once '../config/constValidacion.php';

$aFormulario = [
    'codDepartamento' => '',
    'descDepartamento' => '',
    'fechaBaja' => '',
    'volumenNegocio' => ''
];
$aErrores = [
    'codDepartamento' => '',
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
    $aErrores['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['codDepartamento'], 3, 3, OBLIGATORIO);
    try {
        $sConsulta = <<<QUERY
            SELECT T02_CodDepartamento FROM T02_Departamento
            WHERE T02_CodDepartamento = '{$_REQUEST['codDepartamento']}';
        QUERY;

        // Conexión con la base de datos.
        $oDB = new PDO(HOST, USER, PASSWORD);

        // Mostrado de las excepciones.
        $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ejecución de la consulta.
        $oConsulta = $oDB->prepare($sConsulta);
        $oConsulta->execute();
        
        /**
         * Si la consulta arroja algún resultado, significa que un departamento
         * con ese código ya existe e indicaría un error.
         */
        if($oConsulta->fetchObject()){
            $aErrores['codDepartamento'] = 'Ya existe un departamento con ese código.';
        }
        
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
    $aFormulario['codDepartamento'] = $_REQUEST['codDepartamento'];
    $aFormulario['descDepartamento'] = $_REQUEST['descDepartamento'];
    $aFormulario['volumenNegocio'] = $_REQUEST['volumenNegocio'];

    // Modificación de la base de datos.
    try {
        $sConsulta = <<<QUERY
            INSERT INTO T02_Departamento(T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio) VALUES
            ('{$aFormulario['codDepartamento']}', '{$aFormulario['descDepartamento']}', UNIX_TIMESTAMP() ,{$aFormulario['volumenNegocio']});
        QUERY;

        // Conexión con la base de datos.
        $oDB = new PDO(HOST, USER, PASSWORD);

        // Mostrado de las excepciones.
        $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ejecución de la consulta.
        $oConsulta = $oDB->prepare($sConsulta);
        $oConsulta->execute();

        // Regreso a la página principal
        header('Location: MtoDepartamentos.php');
        exit;
        
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
<!DOCTYPE html>
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
            <h2>Creación de departamento</h2>
        </header>
        <main>
            <?php
            /**
             * Si es la primera vez que se accede a la página, o si se ha enviado
             * el formulario con datos incorectos, se muestra el formulario.
             */
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <fieldset class="acciones">
                    <legend>Nuevo departamento</legend>
                    <table>
                        <tr>
                            <td><label class="obligatorio" for="codDepartamento">Código</label></td>
                            <td><input class="obligatorio" type="text" id="codDepartamento" name="codDepartamento" value="<?php echo $_REQUEST['codDepartamento']??'' ?>"></td>
                            <td><?php echo '<span>' . $aErrores['codDepartamento'] . '</span>' ?></td>
                        </tr>
                        <tr>
                            <td><label class="obligatorio" for="descDepartamento">Descripción</label></td>
                            <td><input class="obligatorio" type="text" id="descDepartamento" name="descDepartamento" value="<?php echo $_REQUEST['descDepartamento']??'' ;?>"></td>
                            <td><?php echo '<span>' . $aErrores['descDepartamento'] . '</span>' ?></td>
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha baja</label></td>
                            <td><input type="text" id="fechaBaja" name="fechaBaja" value="-" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label class="obligatorio" for="volumenNegocio">Volumen negocio</label></td>
                            <td><input class="obligatorio" type="text" id="volumenNegocio" name="volumenNegocio" value="<?php echo $_REQUEST['volumenNegocio']??'' ?>"></td>
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

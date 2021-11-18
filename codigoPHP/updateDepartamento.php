<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra.
    Fecha: 17/11/2021.
-->
<?php
/**
 * @author Sasha
 * @since 17/11/2021
 * Fecha de modificación: 17/11/2021
 * 
 * Ventana de modificación de datos de un departamento.
 */
/*
 * Si se ha seleccionado Cancelar, sale a la ventana anterior.
 */
if (isset($_REQUEST['cancelar'])) {
    header('Location: MtoDepartamentos.php');
}

//Librería de validación.
include '../core/210322ValidacionFormularios.php';

// Constantes para la conexión con la base de datos.
require_once '../config/configDB.php';

// Constantes para el parámetro "obligatorio".
require_once '../config/constValidacion.php';

/*
 * Inicialización de los array de elementos y errores del formulario.
 */
$aFormulario = [
    'codigoDepartamento' => '',
    'descripcionDepartamento' => '',
    'fechaBaja' => '',
    'volumenNegocio' => ''
];
$aErrores = [
    'descripcionDepartamento' => '',
    'volumenNegocio' => ''
];

/**
 * La primera vez que se carga la página, toma de la url el código
 * de departamento y selecciona de la base de datos toda
 * la información sobre él.
 * 
 * Si enviado el formulario pero algo estaba erróneo, vuelve
 * a tomar el código de departamento de la url y selecciona otra
 * vez la información.
 */
try {
    $sConsulta = <<<QUERY
        SELECT * FROM Departamento WHERE codDepartamento = '{$_REQUEST['codDepartamentoEnCurso']}';
    QUERY;

    // Conexión con la base de datos.
    $oDB = new PDO(HOST, USER, PASSWORD);
    $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ejecución de la consulta.
    $oConsulta = $oDB->prepare($sConsulta);
    $oConsulta->execute();
    $oResultadoConsulta = $oConsulta->fetch();

    $aFormulario = [
        'codigoDepartamento' => $oResultadoConsulta['codDepartamento'],
        'descripcionDepartamento' => $oResultadoConsulta['descDepartamento'],
        'fechaBaja' => $oResultadoConsulta['fechaBaja'],
        'volumenNegocio' => $oResultadoConsulta['volumenNegocio']
    ];
    var_dump($aFormulario);
    var_dump($_REQUEST);
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
 * Si el formulario ha sido enviado, valida los campos y registra los errores.
 */
if (isset($_REQUEST['aceptar'])) {
    /*
     * Manejador de errores. Por defecto asume que no hay ningún
     * error (true). Si encuentra alguno se pone a false.
     */
    $bEntradaOK = true;

    /*
     * Registro de errores. Valida todos los campos.
     */
    $aErrores['descripcionDepartamento'] = validacionFormularios::comprobarAlfanumerico($_REQUEST['descripcionDepartamento'], 255, 1, OBLIGATORIO);
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
 */ else {
    $bEntradaOK = false;
}


if ($bEntradaOK) {
    /*
     * Recogida de la información enviada.
     */
    $aFormulario['descripcionDepartamento'] = $_REQUEST['descripcionDepartamento'];
    $aFormulario['volumenNegocio'] = $_REQUEST['volumenNegocio'];

    /*
     * Modificación de la base de datos.
     */
    try {
        $sConsulta = <<<QUERY
                        UPDATE Departamento SET descDepartamento = :modDescripcion,
                        volumenNegocio = :modVolumenNegocio
                        WHERE codDepartamento= '{$aFormulario['codigoDepartamento']}';
                    QUERY;

        // Conexión con la base de datos.
        $oDB = new PDO(HOST, USER, PASSWORD);

        // Mostrado de las excepciones.
        $oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ejecución dela consulta.
        $oConsulta = $oDB->prepare($sConsulta);

        $oConsulta->bindParam(':modDescripcion', $aFormulario['descripcionDepartamento']);
        $oConsulta->bindParam(':modVolumenNegocio', $aFormulario['volumenNegocio']);

        $oConsulta->execute();

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
                position: relative;
            }
            fieldset.envio a{
                position: absolute;
                left: 10%;
            }
            /*
            input[type='submit']{
                position: absolute;
                right: 10%;
            }
            */

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
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?codDepartamentoEnCurso=" . $aFormulario['codigoDepartamento'] ?>" method="post">
                <fieldset class="acciones">
                    <legend>Modificación</legend>
                    <table>
                        <tr>
                            <td><label for="codigoDepartamento">Código</label></td>
                            <td><input type="text" id="codigoDepartamento" name="codigoDepartamento" value="<?php echo $aFormulario['codigoDepartamento'] ?>" disabled></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label class="obligatorio" for="descripcionDepartamento">Descripción</label></td>
                            <td><input class="obligatorio" type="text" id="descripcionDepartamento" name="descripcionDepartamento" value="<?php echo $aFormulario['descripcionDepartamento'] ?>"></td>
                            <td><?php echo '<span>' . $aErrores['descripcionDepartamento'] . '</span>' ?></td>
                        </tr>
                        <tr>
                            <td><label for="fechaBaja">Fecha baja</label></td>
                            <td><input type="text" id="fechaBaja" name="fechaBaja" value="<?php echo $aFormulario['fechaBaja'] ?? '-' ?>" disabled=""></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label class="obligatorio" for="volumenNegocio">Volumen negocio</label></td>
                            <td><input class="obligatorio" type="text" id="volumenNegocio" name="volumenNegocio" value="<?php echo $aFormulario['volumenNegocio'] ?>"></td>
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

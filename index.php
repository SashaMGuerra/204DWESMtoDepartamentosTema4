<!DOCTYPE html>
<!--
    Autor: Isabel Martínez Guerra
    Fecha: 17/11/2021
-->
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link href="webroot/style/mtoDepartamentos.css" rel="stylesheet" type="text/css"/>
        <title>IMG - Mto. Departamentos</title>
        <style>
            main{
                text-align: center;
            }
            a.button{
                border: 3px solid midnightblue;
                background-color: lavender;
                padding: 10px 20px;
                margin-top: 50px;
                font-size: medium;
            }
            div:last-child a.button{
                padding: 5px 10px;
            }
            
            footer{
                text-align: center;
                background-color: midnightblue;
                color: white;
                font-size: 12px;
                position: absolute;
                width: 100%;
                bottom: 0;
            }
            footer > *{
                display: inline;
            }
            footer img{
                margin: 5px;
                width: 25px;
            }
            footer div{
                vertical-align: 80%;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Tema 4 - Mantenimiento Departamentos</h1>
        </header>
        <main>
            <div class="btContainer">
                <a class="button" href="codigoPHP/MtoDepartamentos.php">Ir a la aplicación</a>
            </div>
            <div class="btContainer">
                <a class="button" href="../proyectoTema4/indexProyectoTema4.php">Salir</a>
            </div>
        </main>
        <footer>
            <a href="https://github.com/SashaMGuerra/204DWESMtoDepartamentosTema4"><img src="webroot/media/img/github_logo_white.png" alt=""/></a>
            <div>© 2020-2021 Isabel Martínez Guerra — IES Los Sauces (Benavente, Zamora).</div>
        </footer>
    </body>
</html>

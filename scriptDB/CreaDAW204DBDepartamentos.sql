/* Creación de la base de datos */
CREATE DATABASE IF NOT EXISTS DB204DWESMtoDepartamentosTema4;

USE DB204DWESMtoDepartamentosTema4;

/* Creación de las tablas */
CREATE TABLE IF NOT EXISTS T02_Departamento(
    T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaCreacionDepartamento INT NOT NULL,
    T02_VolumenDeNegocio FLOAT NOT NULL,
    T02_FechaBajaDepartamento DATETIME NULL
) ENGINE=INNODB;

/* Creación del usuario */
CREATE USER IF NOT EXISTS 'User204DWESMtoDepartamentosTema4'@'%' IDENTIFIED BY 'paso';
GRANT ALL ON DB204DWESMtoDepartamentosTema4.* TO 'User204DWESMtoDepartamentosTema4'@'%';
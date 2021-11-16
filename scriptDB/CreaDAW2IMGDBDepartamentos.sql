/* Creación de la base de datos */
CREATE DATABASE IF NOT EXISTS DB204DWESMtoDepartamentosTema4;

USE DB204DWESMtoDepartamentosTema4;

/* Creación de las tablas */
CREATE TABLE IF NOT EXISTS Departamento(
    codDepartamento VARCHAR(3) PRIMARY KEY,
    descDepartamento VARCHAR(255) NOT NULL,
    fechaBaja DATE NULL,
    volumenNegocio FLOAT NULL
) engine=innodb;

/* Creación del usuario */
CREATE USER IF NOT EXISTS 'User204DWESMtoDepartamentosTema4'@'%' IDENTIFIED BY 'paso';
GRANT ALL ON DB204DWESMtoDepartamentosTema4.* TO 'User204DWESMtoDepartamentosTema4'@'%';
/* Inserción en tablas */
USE DB204DWESMtoDepartamentosTema4;

INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenDeNegocio) VALUES
('INF','Departamento de Informatica', UNIX_TIMESTAMP(),1.5),
('BIO','Departamento de Biologia', UNIX_TIMESTAMP(),2.5),
('ING','Departamento de Inglés', UNIX_TIMESTAMP(),3.5),
('LEN','Departamento de Lengua', UNIX_TIMESTAMP(),4.5),
('MUS','Departamento de Musica', UNIX_TIMESTAMP(),1.5);
--Creacion de la DB de prueba
CREATE database PrograGokuKezo
go
--Seleccion de la DB para crear todos los procedures necesarios
use PrograGokuKezo
go
--Procedure que a�ade filegroups a la base de datos...
--Recibe 2 par�metros: el nombre de la base de datos donde se desea el nuevo FG y el nombre del mismo...
Create or alter procedure FilegroupsPlusPlus
(@database nvarchar(50),@fileGName nvarchar(50))
as
DECLARE
	--se declara un query para hacerlo din�mico
	@query nvarchar(200);
 BEGIN
	set @query = 'ALTER DATABASE '+@database+' 
    ADD FILEGROUP '+@fileGName
	EXECUTE sp_executesql @query
end;
go	

--Procedure que crea nuevos discos de archivos a un filegroup asociado a una DB especificada...
--Recibe 7 par�metros: el nombre de la DB y el nombre del FG asociado a la misma, la ruta espec�fica donde se desea que se cree el archivo 
--(la carpeta debe poseer permisos de seguridad), el tama�o inicial, m�ximo y por �ltimo, el valor de crecimiento y el nombre personalizado del mismo.
CREATE or ALTER PROCEDURE DiscosPlusPlus (@nombreDB nvarchar(50),@fileName nvarchar(50),@rutaFis nvarchar(100),@size int, 
								@maxsize int, @growth int, @fg nvarchar(50))
 AS
 DECLARE
	@query nvarchar(500);
 BEGIN
	--se crea el query para hacerlo din�mico
	--con el caso de la direccion se le hace un append con la ruta y se le agregan las ' y \ necesarios, m�s una extensi�n "ExtraFile.ndf"
	set @query = 'ALTER DATABASE '+@nombreDB+'
		ADD FILE
		( 
			NAME = '+@fileName+',
			FILENAME = '''+@rutafis+'\'+@fileName+'ExtraFile.ndf'',
			SIZE = '+(cast(@size as nvarchar(30)))+',
			MAXSIZE = '+(cast(@maxsize as nvarchar(30)))+',
			FILEGROWTH = '+(cast(@growth as nvarchar(30)))+'
		) TO FILEGROUP '+@fg+'
	'
	EXECUTE sp_executesql @query	
END;
GO

--**************Procedures que modifican discos de archivos...**********************--
--Existe uno por cada atributo y uno con todos...
-- Todos reciben de par�metro el nombre de la DB y el nombre del archivo espec�fico, pero cada uno de los
--espec�ficos posee un par�metro especial, unico que representa el valor a cambiar...

--Modificaci�n de Crecimiento
--Recibe de parametro especial el nuevo crecimiento del archivo...
CREATE or ALTER PROCEDURE AlterDiscosGroGro (@nombreDB nvarchar(50),@fileName nvarchar(50),@newgrowth int)
 AS
 DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = '
		--crecimiento
		ALTER DATABASE '+@nombreDB+'  
		MODIFY FILE  
		(NAME = '+@fileName+',  
		FILEGROWTH = '+(cast(@newgrowth as nvarchar(30)))+')
	'
	EXECUTE sp_executesql @query	
END;
GO

--Modificaci�n del tama�o del disco elegido
--Recibe de par�metro especial el nuevo tama�o del disco, debe ser mayor al tama�o actual...
CREATE or ALTER PROCEDURE AlterDiscosSize (@nombreDB nvarchar(50),@fileName nvarchar(50),@newsize int)
 AS
 DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = '
		--crecimiento
		ALTER DATABASE '+@nombreDB+'  
		MODIFY FILE  
		(NAME = '+@fileName+',  
		SIZE = '+(cast(@newsize as nvarchar(30)))+')
	'
	EXECUTE sp_executesql @query	
END;
GO

--Modificaci�n del nombre del disco elegido
--Recibe de par�metro especial el nuevo nombre del disco, este es el nombre l�gico, no el f�sico...
CREATE or ALTER PROCEDURE AlterDiscosName (@nombreDB nvarchar(50),@fileName nvarchar(50), @newname nvarchar(50))
 AS
 DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = '
	--nuevo nombre
		ALTER DATABASE '+@nombreDB+'
		MODIFY FILE  
		(  
			NAME = '+@fileName+',  
			NEWNAME = '+@newName+'
		)
	'
	EXECUTE sp_executesql @query	
END;
GO
--Modificaci�n del tama�o m�ximo del disco elegido
--Recibe de par�metro especial el nuevo tama�o max del disco, debe ser mayor al tama�o actual...
CREATE or ALTER PROCEDURE AlterDiscosMax (@nombreDB nvarchar(50),@fileName nvarchar(50), @newmaxsize int)
 AS
 DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = '
	-- nuevo max size
		ALTER DATABASE '+@nombreDB+'
		MODIFY FILE  
		(  
			NAME = '+@fileName+',  
			MAXSIZE = '+(cast(@newmaxsize as nvarchar(30)))+'
		)
	'
	EXECUTE sp_executesql @query	
END;
GO
--Modificaci�n de todos los atributos del disco elegido
--Recibe de par�metro todos lo atributos anteriores
CREATE or ALTER PROCEDURE AlterDiscosAll (@nombreDB nvarchar(50),@fileName nvarchar(50),@newsize int, 
								@newmaxsize int, @newgrowth int, @newname nvarchar(50))
 AS
 DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = '
		--todos los datos son modi
		ALTER DATABASE '+@nombreDB+'
		MODIFY FILE  
		(  
			NAME = '+@fileName+',  
			NEWNAME = '+@newName+',
			SIZE = '+(cast(@newsize as nvarchar(30)))+',
			MAXSIZE = '+(cast(@newmaxsize as nvarchar(30)))+',
			FILEGROWTH = '+(cast(@newgrowth as nvarchar(30)))+'
		)
	'
	EXECUTE sp_executesql @query	
END;
GO

--**************Procedures que retornan estad�sticas**********************--
--Existe uno por cada atributo: Stats de tama�o, tama�o m�ximo, crecimiento y espacios usados y disponibles de un
--disco de archivos determinado...
-- Todos reciben de par�metro el nombre del disco a realizar el query...


--Procedure que retorna el tama�o total en Mb del disco de archivos...
create or alter procedure CaracSize(@fileName nvarchar(50))
as
begin
	(SELECT (usa.total_page_count*8.0)/1024 as 'Total de MB' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName);
end;
go

--Procedure que retorna el crecimiento en Mb del disco de archivos...
create or alter procedure CaracGrowth(@fileName nvarchar(50))
as
begin
	(SELECT db.growth*8.0/1024 as 'Growth' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName);
end;
go

--Procedure que retorna el tama�o m�ximo en Mb del disco de archivos...
create or alter procedure CaracMax(@fileName nvarchar(50))
as
begin
	(SELECT max_size*8.0/1024 as 'MB Max' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName);
end;
go

--Procedure que retorna el tama�o disponible en Mb del disco de archivos...
create or alter procedure CaracDisp(@fileName nvarchar(50))
as
begin
	(SELECT (usa.unallocated_extent_page_count*8.0)/1024 as 'Total de MB desocupados' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)
end;
go

--Procedure que retorna el tama�o usado en Mb del disco de archivos...
create or alter procedure CaracUsa(@fileName nvarchar(50))
as
begin
	(SELECT (usa.allocated_extent_page_count*8.0)/1024 as 'Total de MB ocupados' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)
end;
go


CREATE database test
go

use test
go
--Procedure anyada filegroups
Create or alter procedure FilegroupsPlusPlus
(@database nvarchar(50),@fileGName nvarchar(50))
as
DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = 'ALTER DATABASE '+@database+' 
    ADD FILEGROUP '+@fileGName
	EXECUTE sp_executesql @query
end;
go	

--exec FilegroupsPlusPlus 'test','Soy_un_manco_en_Dota'
go

--procedure elimina filegroups
Create or alter procedure FilegroupsMinusMinus
(@database nvarchar(50),@fileGName nvarchar(50))
as
DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = 'ALTER DATABASE '+@database+' 
    REMOVE FILEGROUP '+@fileGName
	EXECUTE sp_executesql @query
end;
go	



--Procedure nuevos discos
CREATE or ALTER PROCEDURE DiscosPlusPlus (@nombreDB nvarchar(50),@fileName nvarchar(50),@rutaFis nvarchar(100),@size int, 
								@maxsize int, @growth int, @fg nvarchar(50))
 AS
 DECLARE
	@query nvarchar(500);
 BEGIN
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

/*
exec DiscosPlusPlus 'test','ACM1PT2','C:\Users\Steven\Desktop\TEC\Semestre 5\Bases II',5, 
								20, 5, 'Soy_un_manco_en_Dota'
								*/
go




--Procedure modi files

CREATE or ALTER PROCEDURE AlterDiscos (@nombreDB nvarchar(50),@fileName nvarchar(50),@newsize int, 
								@newmaxsize int, @newgrowth int, @newname nvarchar(50), @mbmenos int, @tipoOP char)
 AS
 DECLARE
	@query nvarchar(1000);
 BEGIN
	set @query = '
		--crecimiento
	IF '+@tipoOP+' = 1
		ALTER DATABASE '+@nombreDB+'  
		MODIFY FILE  
		(NAME = '+@fileName+',  
		FILEGROWTH = '+(cast(@newgrowth as nvarchar(30)))+')
	--reduccion
	ELSE IF '+@tipoOP+' = 2  
		DBCC SHRINKFILE ('+@fileName+', '+(cast(@mbmenos as nvarchar(30)))+')
	--tamanyo
	ELSE IF '+@tipoOP+' = 3
		ALTER DATABASE '+@nombreDB+'
		MODIFY FILE  
		(  
			NAME = '+@fileName+',  
			SIZE = '+(cast(@newsize as nvarchar(30)))+'
		)
	--nuevo nombre
	ELSE IF '+@tipoOP+' = 4
		ALTER DATABASE '+@nombreDB+'
		MODIFY FILE  
		(  
			NAME = '+@fileName+',  
			NEWNAME = '+@newName+'
		)
	-- nuevo max size
	ELSE IF '+@tipoOP+' = 5
		ALTER DATABASE '+@nombreDB+'
		MODIFY FILE  
		(  
			NAME = '+@fileName+',  
			MAXSIZE = '+(cast(@newmaxsize as nvarchar(30)))+'
		)
	--todos los datos
	ELSE
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
  
--exec AlterDiscos 'test','El_Peluca_Sapbee',10,20,7,'El_Peluca_Sapbee',5,'7'
go

--*****************************************************

Create or alter procedure DiscosMinusMinus
(@database nvarchar(50),@fileName nvarchar(50))
as
DECLARE
	@query nvarchar(200);
 BEGIN
	set @query = 'ALTER DATABASE '+@database+'  
		REMOVE FILE '+@fileName; 
	EXECUTE sp_executesql @query
end;
go	

exec DiscosMinusMinus 'test','ACM1PT2'
go



--*********************************************************

create or alter procedure SeleccionDB (@nombreDB nvarchar(100))
as
declare
	@query nvarchar(100)
begin
	set @query = 'use '+@nombreDB;
	execute sp_executesql @query
end;
go

--**********************************************************
--Determinarcaracter�sticas principales: tama�o, crecimiento, tama�o m�ximo, porcentaje de uso.

create or alter procedure CaracPrin (
	@fileName nvarchar(50), 
	@porcenOcu float output,
	@porcenDes float output,
	@maximus float output,
	@growth float output,
	@size float output,
	@empty float output,
	@full float output)
as
begin
set @size = (SELECT (usa.total_page_count*8.0)/1024 as 'Total de MB' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)

set @growth = (SELECT db.growth*8.0/1024 as 'Growth' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)

set @maximus = (SELECT max_size*8.0/1024 as 'MB Max' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)

set @empty = (SELECT (usa.unallocated_extent_page_count*8.0)/1024 as 'Total de MB desocupados' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)

set @full = (SELECT (usa.allocated_extent_page_count*8.0)/1024 as 'Total de MB ocupados' from sys.database_files as db
	inner join (SELECT * FROM sys.dm_db_file_space_usage) as usa on usa.file_id = db.file_id
	inner join (SELECT * FROM sys.sysfiles) as ff on ff.name = db.name where db.name = @fileName)

set @porcenOcu = (@full*100)/(@full+@empty)

set @porcenDes = (@empty*100)/(@full+@empty)



end;
go
   

-- Declare the variable to receive the output value of the procedure.   
declare
	@porcenOcu2 float,
	@porcenDes2 float,
	@maximus2 float,
	@growth2 float,
	@size2 float,
	@empty2 float,
	@full2 float
 
-- Execute the procedure specifying a last name for the input parameter  
-- and saving the output value in the variable @SalesYTDBySalesPerson  

/*
exec CaracPrin 'El_Peluca_Sapbee', @porcenOcu = @porcenOcu2 output, @porcenDes = @porcenDes2 output,
									@maximus = @maximus2 output, @growth = @growth2 output, @size = @size2 output,
									@empty = @empty2 output, @full = @full2 output;
*/

-- Display the value returned by the procedure.   

--print @porcenOcu2

--print @porcenDes2
go


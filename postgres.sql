SELECT datname FROM pg_database;

create or replace function caracPos (nombreDB text)
RETURNS text as
$$
DECLARE
    result text;
begin
	SELECT pg_size_pretty(pg_database_size(nombreDB)) into result;
	return result;
END;
$$
LANGUAGE plpgsql;

--DROP FUNCTION caracpos(text);

SELECT * from caracPos('matricula');

select * from pg_stat_database;

--no se puede hacer sin db_link... al parecer
/*
create or replace function tablespacesPlusPlus (nombreTS text, ruta text)
returns void as
$$
begin
	EXECUTE ('
   CREATE TABLESPACE '|| nombreTS || ' LOCATION ' || quote_literal(ruta));
END;
$$
LANGUAGE plpgsql;
*/


--select * from tablespacesPlusPlus ('pg_prueba','C:/BDCI');

-- asi directo si... 
CREATE TABLESPACE prueba LOCATION 'C:/BDCI';

SELECT spcname FROM pg_tablespace;


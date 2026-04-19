SELECT * FROM information_schema.tables
WHERE table_schema = 'public';

SELECT * FROM leaves 
WHERE start_date = 
(SELECT MAX(start_date) FROM leaves);
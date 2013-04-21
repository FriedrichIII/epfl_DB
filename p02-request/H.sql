--H. List all countries which didn’t ever win a medal.
SELECT c.name AS coname
FROM Countries c
LEFT OUTER JOIN
	(SELECT DISTINCT t.iocCode
	FROM Teams t
	WHERE t.rank > 0 and t.rank < 4) Oc
ON c.iocCode = Oc.iocCode
WHERE Oc.iocCode IS NULL
	
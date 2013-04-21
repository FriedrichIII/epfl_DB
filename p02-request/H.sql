--H. List all countries which didn’t ever win a medal.
SELECT c.name
FROM Countries c
JOIN
	(SELECT cc.iocCode
	FROM Countries cc
	WHERE cc.iocCode NOT IN
		(SELECT DISTINCT t.iocCode
		FROM Teams t
		WHERE t.rank > 0 and t.rank < 4)) Oc
ON c.iocCode = Oc.iocCode
	
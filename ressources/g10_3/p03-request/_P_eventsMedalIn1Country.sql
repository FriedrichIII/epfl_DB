-- List all events for which all medals are won by athletes from the same country
SELECT Ev.name
FROM events Ev
JOIN 	(SELECT Ev1.eventId
	FROM events Ev1
	JOIN teams Te ON Ev1.eventID = Te.eventID
	WHERE Te.rank < 4 AND Te.rank > 0
	GROUP BY Ev1.eventID
	HAVING COUNT(DISTINCT Te.ioccode) = 1) EvIds
ON Ev.eventID = EvIds.eventID
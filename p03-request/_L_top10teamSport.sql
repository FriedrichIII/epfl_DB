-- List top 10 nations according to their success in team sports. Use average number of medalists for each 
-- medal awarded to a particular nation.
-- Interpretation : number of MEDALIST not number of TEAMS
CREATE OR REPLACE VIEW MedalMultiTeams AS
SELECT Te.teamID
FROM memberships Me
JOIN teams Te ON Te.teamID = Me.teamID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Te.teamID
HAVING COUNT(*) > 1;

CREATE OR REPLACE VIEW Full_MedalMultiTeam AS
SELECT Te.*
FROM MedalMultiTeams MMT
JOIN teams Te ON Te.teamID = MMT.teamID;

CREATE OR REPLACE VIEW Ga_Co_TeamMedaCount AS
SELECT Ga.gameID, Co.ioccode, COUNT(*) AS teamMedalists
FROM memberships Me
JOIN Full_MedalMultiTeam Te ON Me.teamID = Te.teamID
JOIN countries Co ON Co.ioccode = Te.ioccode
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN games Ga ON Ga.gameID = Ev.gameID
WHERE Te.rank < 4 AND Te.rank > 0
GROUP BY Ga.gameID, Co.ioccode;

CREATE OR REPLACE VIEW Co_AvgTeamMedalist AS
SELECT Te.ioccode, AVG(Te.teamMedalists) AS avgMedalists
FROM Ga_Co_TeamMedaCount Te
GROUP BY Te.gameID;

SELECT Co.name, Te.avgMedalists AS avgTeamMedalistCount
FROM Co_AvgTeamMedalist Te
JOIN countries Co ON Te.ioccode = Co.ioccode
ORDER BY Te.avgMedalists DESC
LIMIT 10
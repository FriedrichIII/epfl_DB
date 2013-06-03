-- List all nations whose first medal was gold, all nations whose first medal was silver and all nations 
-- whose first medal was bronze. If nation won more than one medal at the first Olympics it won a medal, 
-- consider that it won the “shinier” medal first. For example if a country didn’t win any medals before 
-- games in 1960 and then it won a gold and a bronze, then its first medal is a gold.

CREATE OR REPLACE VIEW Se_Ye_Co_Medal AS
SELECT DISTINCT Ga.seasonName, Ga.year, Co.ioccode, Te.rank
FROM countries Co
JOIN teams Te ON Co.ioccode = Te.ioccode
JOIN events Ev ON Te.eventID = Ev.eventID
JOIN games Ga ON Ga.gameID = Ev.gameID
WHERE Te.rank < 4 AND Te.rank > 0;

CREATE OR REPLACE VIEW Co_firstMedals AS
SELECT Me_1.ioccode, Me_1.rank
FROM Se_Ye_Co_Medal Me_1
LEFT OUTER JOIN Se_Ye_Co_Medal Me_2
ON Me_1.ioccode = Me_2.ioccode AND (Me_1.year > Me_2.year OR (Me_1.year = Me_2.year AND Me_1.seasonName > Me_2.seasonName))
WHERE Me_2.year IS NULL;

CREATE OR REPLACE VIEW Co_firstMedal AS
SELECT FM_1.*
FROM Co_firstMedals FM_1
LEFT OUTER JOIN Co_firstMedals FM_2
ON (FM_1.rank > FM_2.rank)
WHERE FM_2.ioccode IS NULL;

SELECT Co.name, CASE FM.rank WHEN 1 THEN 'Gold' WHEN 2 THEN 'Silver' WHEN 3 THEN 'Bronze' ELSE 'ERROR' END AS firstMedal
FROM countries Co
JOIN Co_firstMedal FM ON Co.ioccode = FM.ioccode
ORDER BY FM.rank ASC


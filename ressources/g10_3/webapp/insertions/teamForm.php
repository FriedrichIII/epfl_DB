<?php include '../includes/header.php'; ?>
<h3>Insert team:</h3>
<form action="execute.php" method="get">
<p>
athletes: <input type="text" name="athletes_field"><br />
country code: <input type="text" name="country_code_field"><br />
discipline: <input type="text" name="discipline_field"><br />
year: <input type="text" name="year_field"><br />
season: <input type="radio" name="season" value="Summer"> Summer
        <input type="radio" name="season" value="Winter"> Winter <br />
rank: <input type ="text" name="rank_field"><br />
</p>
<input type="hidden" name="entry_type" value="team">
<input type="submit" name="action" value="Insert">
<input type="submit" name="action" value="Delete">
</form>
<?php include '../includes/footer.php'; ?>
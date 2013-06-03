<?php include '../includes/header.php'; ?>
<h3>Insert event:</h3>
<form action="execute.php" method="get">
<p>
discipline: <input type="text" name="discipline_field"><br />
year: <input type="text" name="year_field"><br />
season: <input type="radio" name="season" value="Summer"> Summer 
        <input type="radio" name="season" value="Winter"> Winter 
</p>
<input type="hidden" name="entry_type" value="event">
<input type="submit" name="action" value="Insert">
<input type="submit" name="action" value="Delete">
</form>
<?php include '../includes/footer.php'; ?>

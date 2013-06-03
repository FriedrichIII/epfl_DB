<?php include '../includes/header.php'; ?>
<h3>Choose a game:</h3>
<form action="medalTableByGame.php" method="get">
<p>
year: <input type="text" name="year_field"><br />
season: <input type="radio" name="season" value="Summer"> Summer 
        <input type="radio" name="season" value="Winter"> Winter 
</p>
<input type="submit" value="Show">
</form>
<?php include '../includes/footer.php'; ?>
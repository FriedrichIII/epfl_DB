<?php include '../includes/header.php'; ?>
<h3>Insert discipline:</h3>
<form action="execute.php" method="get">
<p>
name: <input type="text" name="name_field"><br />
sport: <input type="text" name="sport_field">
</p>
<input type="hidden" name="entry_type" value="discipline">
<input type="submit" name="action" value="Insert">
<input type="submit" name="action" value="Delete">
</form>
<?php include '../includes/footer.php'; ?>

<?php include '../includes/header.php'; ?>
<h3>Insert country:</h3>
<form action="execute.php" method="get">
<p>
name: <input type="text" name="name_field"><br />
country code: <input type="text" name="country_code_field">
</p>
<input type="hidden" name="entry_type" value="country">
<input type="submit" name="action" value="Insert">
<input type="submit" name="action" value="Delete">
</form>
<?php include '../includes/footer.php'; ?>

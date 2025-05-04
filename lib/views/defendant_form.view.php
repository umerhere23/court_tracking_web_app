<h2>Add Defendant</h2>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="/court_tracking_web_app/public/defendant/add">
    <label>Name: <input type="text" name="name" required></label><br>
    <label>DOB: <input type="date" name="dob" required></label><br>
    <label>Address: <input type="text" name="address"></label><br>
    <label>Ethnicity: <input type="text" name="ethnicity"></label><br>
    <label>Phone: <input type="text" name="phone"></label><br>
    <label>Email: <input type="email" name="email"></label><br>
    <button type="submit">Add Defendant</button>
</form>
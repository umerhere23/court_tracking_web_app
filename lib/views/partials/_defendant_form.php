
<form method="GET" action="/"> <!-- action and method to be CHANGED when database -->
    <label>Name: <input type="text" name="name" required></label><br>
    <label>DOB: <input type="date" name="dob" required></label><br>
    <label>Address: <input type="text" name="address"></label><br>
    <label>Ethnicity: <input type="text" name="ethnicity"></label><br>
    <label>Phone: <input type="text" name="phone"></label><br>
    <label>Email: <input type="email" name="email"></label><br>
    <button type="submit"><?= $submit_label ?? 'Submit' ?></button>
</form>

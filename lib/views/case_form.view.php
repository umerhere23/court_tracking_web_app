<h2>Add Case</h2>

<?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="/court_tracking_web_app/public/case/add">
    <label>Choose Defendant:</label>
    <select name="defendant_ID" required>
        <option value="">-- Select Defendant --</option>
        <?php foreach ($defendants as $defendant): ?>
            <option value="<?= htmlspecialchars($defendant['defendant_ID']) ?>">
                <?= htmlspecialchars($defendant['Name']) ?> (ID: <?= $defendant['defendant_ID'] ?>)
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Add Case</button>
</form>
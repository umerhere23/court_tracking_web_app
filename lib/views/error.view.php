<?php if (!empty($message)): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php else: ?>
    <p>An unexpected error occurred.</p>
<?php endif; ?>

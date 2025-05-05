<div class="mb-3">
    <input type="text" class="form-control mb-2" name="event_description" placeholder="Event Description"
           value="<?= htmlspecialchars($prefill['event_description'] ?? '') ?>">
    <input type="date" class="form-control mb-2" name="event_date"
           value="<?= htmlspecialchars($prefill['event_date'] ?? '') ?>">
    <input type="text" class="form-control mb-3" name="event_location" placeholder="Event Location"
           value="<?= htmlspecialchars($prefill['event_location'] ?? '') ?>">
</div>
<div class="mb-3">
  <label class="form-label">Event Description</label>
  <input 
    type="text" 
    class="form-control" 
    name="description" 
    value="<?= htmlspecialchars($event[0]["Description"]?? '') ?>"
    >

    <label class="form-label">Event Date</label>
  <input 
    type="date" 
    class="form-control" 
    name="date" 
    value="<?= htmlspecialchars($event[0]["Date"]?? '') ?>"
    >

    <label class="form-label">Event Location</label>
  <input 
    type="text" 
    class="form-control" 
    name="location" 
    value="<?= htmlspecialchars($event[0]["Location"]?? '') ?>"
    >
</div>
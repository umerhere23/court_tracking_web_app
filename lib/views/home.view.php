<!-- lib/views/home.view.php -->
<div class="p-5 mb-4 bg-light rounded-3">
  <div class="container-fluid py-5">
    <h1 class="display-5 fw-bold">Welcome to the Court Outcome Tracking System</h1>
    <p class="col-md-8 fs-4">Log in or select a module from the menu to get started.</p>
  </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 my-4">
  <!-- Add Defendant Card -->
  <div class="col">
    <a href="/court_tracking_web_app/public/defendant/add" class="text-decoration-none">
      <div class="card h-100 shadow-sm border-primary">
        <div class="card-body">
          <h5 class="card-title text-primary">Add Defendant</h5>
          <p class="card-text">Create a new defendant profile with personal and contact information.</p>
        </div>
      </div>
    </a>
  </div>

  <!-- Add Case Card -->
  <div class="col">
    <a href="/court_tracking_web_app/public/case/add" class="text-decoration-none">
      <div class="card h-100 shadow-sm border-primary">
        <div class="card-body">
          <h5 class="card-title text-primary">Add Case</h5>
          <p class="card-text">Start a new case and assign it to an existing defendant.</p>
        </div>
      </div>
    </a>
  </div>

</div>

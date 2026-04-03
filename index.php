<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get 3 random pets
$featured = $conn->query("SELECT * FROM pets ORDER BY RAND() LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Pawfect | Home</title>
</head>

<body>

    <!-- NAVBAR resused throughout -->
    <?php include 'includes/navbar.php'; ?>

    <!-- HERO -->
<section class="hero">
  <div class="hero-content fade-in">
    <h1>Find Your Perfect Companion</h1>
    <p>Browse, save, and adopt your next best friend</p>

    <a href="view_all.php" class="btn btn-light mt-3 me-2">Browse Pets</a>
    
  </div>
</section>

<!-- FEATURED PETS -->
<div class="container py-5">

  <div class="text-center mb-4 fade-in">
    <h2>Featured Pets</h2>
    <p>Meet some of our adorable friends looking for a home</p>
  </div>

  <div class="row">

    <?php while ($pet = $featured->fetch_assoc()): ?>

      <div class="col-md-4 mb-4 fade-in">
        <a href="pet.php?id=<?php echo $pet['id']; ?>" class="card-link text-decoration-none text-dark">

          <div class="card h-100 shadow-sm pet-card">

            <img src="uploads/<?php echo $pet['image']; ?>" 
                 class="card-img-top" 
                 style="height: 250px; object-fit: cover;">

            <div class="card-body">

              <h5 class="card-title d-flex align-items-center gap-2">
                <?php echo htmlspecialchars($pet['name']); ?>

                <?php if ($pet['status'] === 'reserved'): ?>
                  <span class="badge bg-warning text-dark">Reserved</span>
                <?php endif; ?>
              </h5>

              <p class="card-text">
                <?php echo $pet['breed']; ?><br>
                <?php echo $pet['age']; ?> years old
              </p>

            </div>

          </div>

        </a>
      </div>

    <?php endwhile; ?>

  </div>
</div>
  
  <?php include 'includes/footer.php'; ?>

<script>

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
    }
  });
});

document.querySelectorAll('.fade-in').forEach(el => {
  observer.observe(el);
});
    </script>
</body>
</html>
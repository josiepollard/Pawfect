<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters
$species = $_GET['species'] ?? '';
$gender = $_GET['gender'] ?? '';
$size = $_GET['size'] ?? '';
$energy = $_GET['energy'] ?? '';
$kids = $_GET['kids'] ?? '';
$pets = $_GET['pets'] ?? '';
$age = $_GET['age'] ?? '';
$sort = $_GET['sort'] ?? '';

// Base query
$sql = "SELECT * FROM pets WHERE 1=1";
$params = [];
$types = "";

// Add filters dynamically
if ($species && $species != 'All') {
    $sql .= " AND species = ?";
    $params[] = $species;
    $types .= "s";
}

if ($gender) {
    $sql .= " AND gender = ?";
    $params[] = $gender;
    $types .= "s";
}

if ($size) {
    $sql .= " AND size = ?";
    $params[] = $size;
    $types .= "s";
}

if ($energy) {
    $sql .= " AND energy_level = ?";
    $params[] = $energy;
    $types .= "s";
}

if ($kids !== '') {
    $sql .= " AND good_with_kids = ?";
    $params[] = $kids;
    $types .= "i";
}

if ($pets !== '') {
    $sql .= " AND good_with_pets = ?";
    $params[] = $pets;
    $types .= "i";
}

// Age ranges
if ($age == "young") {
    $sql .= " AND age <= 2";
}
if ($age == "adult") {
    $sql .= " AND age BETWEEN 3 AND 6";
}
if ($age == "senior") {
    $sql .= " AND age >= 7";
}

$orderBy = "date_added DESC"; // default

if ($sort == "oldest") {
    $orderBy = "date_added ASC";
} elseif ($sort == "age_asc") {
    $orderBy = "age ASC";
} elseif ($sort == "age_desc") {
    $orderBy = "age DESC";
}

$sql .= " ORDER BY $orderBy";

// Prepare + execute
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
 

  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Pawfect | View All</title>
</head>

<body>
 <?php include 'includes/navbar.php'; ?>

<div class="container mt-4">

  <h2 class="mb-4">All Animals</h2>

  <!-- FILTER TOGGLE BUTTON -->


    <div class="col-md-2 d-grid">
        <button class="btn btn-filter-toggle mb-3" data-bs-toggle="collapse" data-bs-target="#filterMenu">
        Filters
        </button>
    </div>

<!-- COLLAPSIBLE FILTER MENU -->
<div class="collapse" id="filterMenu">

  <div class="filter-box p-4 mb-4">

    <form method="GET" class="row g-3">

      <div class="col-md-2">
        <select name="species" class="form-select">
          <option value="">All Species</option>
          <option value="Dog">Dog</option>
          <option value="Cat">Cat</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="gender" class="form-select">
          <option value="">Any Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="size" class="form-select">
          <option value="">Any Size</option>
          <option>Small</option>
          <option>Medium</option>
          <option>Large</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="energy" class="form-select">
          <option value="">Energy</option>
          <option>Low</option>
          <option>Medium</option>
          <option>High</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="age" class="form-select">
          <option value="">Age</option>
          <option value="young">0–2</option>
          <option value="adult">3–6</option>
          <option value="senior">7+</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="kids" class="form-select">
          <option value="">Kids?</option>
          <option value="1">Kids</option>
          <option value="0">No kids</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="pets" class="form-select">
          <option value="">Other Pets?</option>
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select>
      </div>

      <div class="col-md-2">
        <select name="sort" class="form-select">
          <option value="">Sort</option>
          <option value="newest">Newest</option>
          <option value="oldest">Oldest</option>
          <option value="age_asc">Young → Old</option>
          <option value="age_desc">Old → Young</option>
        </select>
      </div>

      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-filter">Filter</button>
      </div>

    </form>

  </div>
</div>

<div class="row">
  <?php if ($result->num_rows > 0): ?>

  <div class="row">

    <?php while ($row = $result->fetch_assoc()): ?>

      <div class="col-md-4 mb-4">
  <a href="pet.php?id=<?php echo $row['id']; ?>" class="card-link">

    <div class="card h-100 shadow-sm pet-card">

      <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" style="height: 350px; object-fit: cover;">

      <div class="card-body">

        <h5 class="card-title"><?php echo $row['name']; ?></h5>

        <p class="card-text">
          <strong><?php echo $row['breed']; ?></strong><br>
          <?php echo $row['age']; ?> years old<br>
          <?php echo $row['gender']; ?>
        </p>

        <p>
          Energy: <?php echo $row['energy_level']; ?><br>
          Size: <?php echo $row['size']; ?>
        </p>

        <p class="small text-muted">
          <?php echo substr($row['description'], 0, 80); ?>...
        </p>

      </div>

    </div>

  </a>
</div>

    <?php endwhile; ?>

  </div>

<?php else: ?>

  <div class="text-center mt-5">
    <h4>No pets found</h4>
  </div>

<?php endif; ?>

  </div>
</div>

</body>
</html>
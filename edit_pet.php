<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 0;

// GET CURRENT DATA
$stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Pet not found");
}

$pet = $result->fetch_assoc();

$message = "";

// HANDLE DELETE
if (isset($_POST['delete'])) {

    // Optional: delete image file too
    $imagePath = "uploads/" . $pet['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $stmt = $conn->prepare("DELETE FROM pets WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_all.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error deleting</div>";
    }
}

// HANDLE UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {

    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $size = $_POST['size'];
    $energy = $_POST['energy_level'];
    $description = $_POST['description'];

    $vaccinated = isset($_POST['vaccinated']) ? 1 : 0;
    $neutered = isset($_POST['neutered']) ? 1 : 0;
    $kids = isset($_POST['good_with_kids']) ? 1 : 0;
    $pets = isset($_POST['good_with_pets']) ? 1 : 0;

    $newImageName = $pet['image']; // default = keep old image

    // =========================
    // HANDLE IMAGE REPLACEMENT
    // =========================
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];

        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExt, $allowed) && $imageSize < 2000000) {

            // Generate new filename
            $newImageName = uniqid("pet_", true) . "." . $imageExt;
            $uploadPath = "uploads/" . $newImageName;

            if (move_uploaded_file($imageTmp, $uploadPath)) {

                // DELETE OLD IMAGE
                $oldImagePath = "uploads/" . $pet['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

            }
        }
    }

    $stmt = $conn->prepare("UPDATE pets SET 
        name=?, species=?, breed=?, age=?, gender=?, size=?, energy_level=?, description=?, 
        vaccinated=?, neutered=?, good_with_kids=?, good_with_pets=?, image=?
        WHERE id=?");

    $stmt->bind_param("sssissssiiissi",
        $name, $species, $breed, $age, $gender, $size,
        $energy, $description, $vaccinated, $neutered, $kids, $pets, $newImageName, $id
    );

    if ($stmt->execute()) {
        header("Location: pet.php?id=" . $id);
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Error updating</div>";
    }

    // Refresh data
    $stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $pet = $stmt->get_result()->fetch_assoc();
}
?>

<?php
require_once 'includes/auth.php';
requireLogin();
requireAdmin();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
  <title>Pawfect | Edit <?php echo $pet['name']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

 <?php include 'includes/navbar.php'; ?>

<div class="container mt-4">

  <h2>Edit <?php echo $pet['name']; ?>'s Details</h2>

  <?php echo $message; ?>

  <form method="POST" enctype="multipart/form-data">

  <!-- Name -->
  <div class="mb-3">
    <label class="form-label">Pet Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo $pet['name']; ?>" required>
  </div>

  <!-- Species -->
  <div class="mb-3">
    <label class="form-label">Species</label>
    <select name="species" class="form-select">
      <option value="Dog" <?php if($pet['species']=="Dog") echo "selected"; ?>>Dog</option>
      <option value="Cat" <?php if($pet['species']=="Cat") echo "selected"; ?>>Cat</option>
    </select>
  </div>

  <!-- Breed -->
  <div class="mb-3">
    <label class="form-label">Breed</label>
    <input type="text" name="breed" class="form-control" value="<?php echo $pet['breed']; ?>">
  </div>

  <!-- Age -->
  <div class="mb-3">
    <label class="form-label">Age</label>
    <input type="number" name="age" class="form-control" value="<?php echo $pet['age']; ?>">
  </div>

  <!-- Gender -->
  <div class="mb-3">
    <label class="form-label">Gender</label>
    <select name="gender" class="form-select">
      <option value="Male" <?php if($pet['gender']=="Male") echo "selected"; ?>>Male</option>
      <option value="Female" <?php if($pet['gender']=="Female") echo "selected"; ?>>Female</option>
    </select>
  </div>

  <!-- Size -->
  <div class="mb-3">
    <label class="form-label">Size</label>
    <select name="size" class="form-select">
      <option value="Small" <?php if($pet['size']=="Small") echo "selected"; ?>>Small</option>
      <option value="Medium" <?php if($pet['size']=="Medium") echo "selected"; ?>>Medium</option>
      <option value="Large" <?php if($pet['size']=="Large") echo "selected"; ?>>Large</option>
    </select>
  </div>

  <!-- Energy -->
  <div class="mb-3">
    <label class="form-label">Energy Level</label>
    <select name="energy_level" class="form-select">
      <option value="Low" <?php if($pet['energy_level']=="Low") echo "selected"; ?>>Low</option>
      <option value="Medium" <?php if($pet['energy_level']=="Medium") echo "selected"; ?>>Medium</option>
      <option value="High" <?php if($pet['energy_level']=="High") echo "selected"; ?>>High</option>
    </select>
  </div>

  <!-- Description -->
  <div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control"><?php echo $pet['description']; ?></textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Replace Image</label>
    <input type="file" name="image" class="form-control" accept="image/*">
  </div>


  <!-- Checkboxes -->
  <div class="mb-3">
    <label class="form-label d-block">Additional Info</label>

    <div class="form-check">
      <input type="checkbox" id="vaccinated" name="vaccinated" class="form-check-input" <?php if($pet['vaccinated']) echo "checked"; ?>>
      <label for="vaccinated" class="form-check-label">Vaccinated</label>
    </div>

    <div class="form-check">
      <input type="checkbox" id="neutered" name="neutered" class="form-check-input" <?php if($pet['neutered']) echo "checked"; ?>>
      <label for="neutered" class="form-check-label">Neutered</label>
    </div>

    <div class="form-check">
      <input type="checkbox" id="kids" name="good_with_kids" class="form-check-input" <?php if($pet['good_with_kids']) echo "checked"; ?>>
      <label for="kids" class="form-check-label">Good with kids</label>
    </div>

    <div class="form-check">
      <input type="checkbox" id="pets" name="good_with_pets" class="form-check-input" <?php if($pet['good_with_pets']) echo "checked"; ?>>
      <label for="pets" class="form-check-label">Good with pets</label>
    </div>

  </div>



  <div class="mt-3 d-flex gap-2">

  <button class="btn btn-update">Update</button>

  <button type="submit" name="delete" class="btn btn-delete"
    onclick="return confirm('Are you sure you want to delete <?php echo $pet['name']; ?>?');">
    Delete 
  </button>

</div>

</form>

</div>
<?php include 'includes/footer.php'; ?>

</body>
</html>
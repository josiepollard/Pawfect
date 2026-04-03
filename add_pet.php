<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Form data
    $name = $_POST['name'] ?? '';
    $species = $_POST['species'] ?? '';
    $breed = $_POST['breed'] ?? '';
    $age = $_POST['age'] ?? 0;
    $gender = $_POST['gender'] ?? '';
    $size = $_POST['size'] ?? '';
    $energy = $_POST['energy_level'] ?? '';
    $description = $_POST['description'] ?? '';

    // Checkboxes
    $vaccinated = isset($_POST['vaccinated']) ? 1 : 0;
    $neutered = isset($_POST['neutered']) ? 1 : 0;
    $kids = isset($_POST['good_with_kids']) ? 1 : 0;
    $pets = isset($_POST['good_with_pets']) ? 1 : 0;

    // =====================
    // IMAGE UPLOAD (SAFE)
    // =====================
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];

        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageExt, $allowed)) {

            if ($imageSize < 2000000) {

                $newImageName = uniqid("pet_", true) . "." . $imageExt;
                $uploadPath = "uploads/" . $newImageName;

                if (move_uploaded_file($imageTmp, $uploadPath)) {

                    // INSERT INTO DATABASE
                    $stmt = $conn->prepare("INSERT INTO pets 
                    (name, species, breed, age, gender, size, energy_level, description, vaccinated, neutered, good_with_kids, good_with_pets, image)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                    $stmt->bind_param("sssissssiiiis",
                        $name, $species, $breed, $age, $gender, $size,
                        $energy, $description, $vaccinated, $neutered, $kids, $pets, $newImageName
                    );

                    if ($stmt->execute()) {
                        $message = "<div class='alert alert-success'>Pet added successfully 🐾</div>";
                    } else {
                       $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
                    }

                } else {
                    $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
                }

                

            } else {
                $message = "<div class='alert alert-danger'>Image too large (max 2MB).</div>";
            }

        } else {
            $message = "<div class='alert alert-danger'>Invalid file type.</div>";
        }

    } else {
        $message = "<div class='alert alert-danger'>Please select an image.</div>";
    }
}
?>

<?php
require_once 'includes/auth.php';
requireLogin();
requireAdmin();
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
    <title>Pawfect | Add pet</title>
</head>

<body>

    <!-- NAVBAR resused throughout -->
    <?php include 'includes/navbar.php'; ?>

        <form method="POST" action="add_pet.php" enctype="multipart/form-data" class="container mt-4">

        <h2>Add New Pet</h2>
        <?php echo $message; ?>

        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">Pet Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- Species -->
        <div class="mb-3">
            <label class="form-label">Species</label>
            <select name="species" class="form-control">
            <option>Dog</option>
            <option>Cat</option>
            <option>Other</option>
            </select>
        </div>

        <!-- Breed -->
        <div class="mb-3">
            <label class="form-label">Breed</label>
            <input type="text" name="breed" class="form-control">
        </div>

        <!-- Age -->
        <div class="mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control">
        </div>

        <!-- Gender -->
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-control">
            <option>Male</option>
            <option>Female</option>
            </select>
        </div>

        <!-- Size -->
        <div class="mb-3">
            <label class="form-label">Size</label>
            <select name="size" class="form-control">
            <option>Small</option>
            <option>Medium</option>
            <option>Large</option>
            </select>
        </div>

        <!-- Energy -->
        <div class="mb-3">
            <label class="form-label">Energy Level</label>
            <select name="energy_level" class="form-control">
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
            </select>
        </div>

        <!-- Checkboxes -->
        <div class="form-check">
            <input type="checkbox" name="vaccinated" class="form-check-input">
            <label class="form-check-label">Vaccinated</label>
        </div>

        <div class="form-check">
            <input type="checkbox" name="neutered" class="form-check-input">
            <label class="form-check-label">Neutered</label>
        </div>

        <div class="form-check">
            <input type="checkbox" name="good_with_kids" class="form-check-input">
            <label class="form-check-label">Good with kids</label>
        </div>

        <div class="form-check">
            <input type="checkbox" name="good_with_pets" class="form-check-input">
            <label class="form-check-label">Good with other pets</label>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Pet Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" name="submit" class="btn btn-submit">
            Add Pet
        </button>

        </form>

        <?php include 'includes/footer.php'; ?>

    <script>

    </script>
</body>
</html>


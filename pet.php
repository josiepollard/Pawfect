<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM pets WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Pet not found");
}

$pet = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
  <title>Pawfect | <?php echo $pet['name']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/index.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
</head>

<body>

 <?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

  <div class="row">

    <div class="col-md-6">
      <img src="uploads/<?php echo $pet['image']; ?>" class="img-fluid rounded">
    </div>

    <div class="col-md-6">

      <h2><?php echo $pet['name']; ?></h2>

      <p><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
      <p><strong>Age:</strong> <?php echo $pet['age']; ?> years</p>
      <p><strong>Gender:</strong> <?php echo $pet['gender']; ?></p>
      <p><strong>Size:</strong> <?php echo $pet['size']; ?></p>
      <p><strong>Energy:</strong> <?php echo $pet['energy_level']; ?></p>

      <hr>

      <p><?php echo $pet['description']; ?></p>

      <hr>

      <p>
        <?php if ($pet['good_with_kids']) echo "✓ Good with kids<br>"; ?>
        <?php if ($pet['good_with_pets']) echo "✓ Good with pets<br>"; ?>
        <?php if ($pet['vaccinated']) echo "✓ Vaccinated<br>"; ?>
        <?php if ($pet['neutered']) echo "✓ Neutered<br>"; ?>
      </p>

        <a href="edit_pet.php?id=<?php echo $pet['id']; ?>" class="btn btn_edit mt-3">
        Edit 
        </a>
    </div>

  </div>

</div>
<?php include 'includes/footer.php'; ?>

</body>
</html>
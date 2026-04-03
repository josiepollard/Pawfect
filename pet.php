<?php
session_start();
$role = $_SESSION['role'] ?? null;
$userName = $_SESSION['name'] ?? '';
$userEmail = $_SESSION['email'] ?? '';

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
$isFav = false;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT id FROM favourites WHERE user_id=? AND pet_id=?");
    $stmt->bind_param("ii", $userId, $id);
    $stmt->execute();
    $res = $stmt->get_result();

    $isFav = $res->num_rows > 0;
}

// HANDLE ENQUIRY
if (isset($_POST['enquire'])) {

    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO enquiries (pet_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id, $user_name, $email, $message);

    if ($stmt->execute()) {
        $success = "Enquiry sent successfully 🐾";
    } else {
        $success = "Error sending enquiry";
    }
}
// TOGGLE RESERVE (ADMIN ONLY)
if (isset($_POST['toggle_reserve']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {

    // Flip status
    $newStatus = ($pet['status'] === 'reserved') ? 'available' : 'reserved';

    $stmt = $conn->prepare("UPDATE pets SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $id);

    if ($stmt->execute()) {
        header("Location: pet.php?id=" . $id);
        exit();
    }
}


// HANDLE FAVOURITE TOGGLE
if (isset($_POST['toggle_fav']) && isset($_SESSION['user_id'])) {

    $userId = $_SESSION['user_id'];

    // Check if already saved
    $check = $conn->prepare("SELECT id FROM favourites WHERE user_id=? AND pet_id=?");
    $check->bind_param("ii", $userId, $id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // Remove favourite
        $del = $conn->prepare("DELETE FROM favourites WHERE user_id=? AND pet_id=?");
        $del->bind_param("ii", $userId, $id);
        $del->execute();
    } else {
        // Add favourite
        $add = $conn->prepare("INSERT INTO favourites (user_id, pet_id) VALUES (?, ?)");
        $add->bind_param("ii", $userId, $id);
        $add->execute();
    }

    header("Location: pet.php?id=" . $id);
    exit();
}
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

    <div class="col-md-6 position-relative">

  <img src="uploads/<?php echo $pet['image']; ?>" class="img-fluid rounded w-100">

  <!-- HEART BUTTON OVERLAY -->
  <?php if (isset($_SESSION['user_id'])): ?>
    <form method="POST" class="fav-overlay">
      <button type="submit" name="toggle_fav" class="btn btn-heart">

        <?php if ($isFav): ?>
          <i class="fa fa-heart text-danger"></i>
        <?php else: ?>
          <i class="fa fa-heart-o text-dark"></i>
        <?php endif; ?>

      </button>
    </form>
  <?php endif; ?>

</div>

    <div class="col-md-6">


    <?php if(isset($success)) echo "<div class='alert alert-success mt-3'>$success</div>"; ?>

 

      <h2>
        <?php echo htmlspecialchars($pet['name']); ?>

        <?php if ($pet['status'] === 'reserved'): ?>
          <span class="badge bg-warning text-dark ms-2">Reserved</span>
        <?php endif; ?>
      </h2>

      

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

      
        <!-- Admin only -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

        <a href="edit_pet.php?id=<?php echo $pet['id']; ?>" class="btn btn_edit mt-3">
        Edit 
        </a>


        
          <form method="POST" class="mt-2">

          <button type="submit" name="toggle_reserve"
            class="btn btn-reserve">

            <?php if ($pet['status'] === 'reserved'): ?>
              Unreserve
            <?php else: ?>
              Mark as Reserved
            <?php endif; ?>

          </button>

        </form>
        
      <?php endif; ?>

       <!-- SHOW TO NON-ADMINS ONLY AND NOT RESERVED -->
<?php if ($role !== 'admin' && $pet['status'] !== 'reserved'): ?>

  <button class="btn btn-adopt mt-3" data-bs-toggle="collapse" data-bs-target="#enquiryForm">
    Adopt Me 
  </button><br>

  <div class="collapse mt-3" id="enquiryForm">

    <div class="card p-3">

      <h5>Send Enquiry</h5>

      <form method="POST">

        <input 
          type="text" 
          name="user_name" 
          class="form-control mb-2" 
          placeholder="Your Name" 
          value="<?php echo htmlspecialchars($userName); ?>"
          required
        >

        <input 
          type="email" 
          name="email" 
          class="form-control mb-2" 
          placeholder="Your Email" 
          value="<?php echo htmlspecialchars($userEmail); ?>"
          required
        >

        <textarea 
          name="message" 
          class="form-control mb-2" 
          placeholder="Message" 
          required
        ></textarea>

        <button type="submit" name="enquire" class="btn btn-sendEnuiry">
          Send Enquiry
        </button>

      </form>

    </div>

  </div>

<?php endif; ?>
    </div>

  </div>

</div>
<?php include 'includes/footer.php'; ?>

</body>
</html>
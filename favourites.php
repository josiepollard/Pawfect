<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// HANDLE FAVOURITE TOGGLE (FROM THIS PAGE)
if (isset($_POST['toggle_fav']) && isset($_SESSION['user_id'])) {

    $petId = $_POST['pet_id'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM favourites WHERE user_id=? AND pet_id=?");
    $stmt->bind_param("ii", $userId, $petId);
    $stmt->execute();

    // Refresh page
    header("Location: favourites.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT pets.* 
    FROM favourites 
    JOIN pets ON favourites.pet_id = pets.id
    WHERE favourites.user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
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

<div class="container mt-4">

  <h2>Saved Pets</h2>

  <div class="row">

    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>

        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm position-relative">

  <!-- HEART BUTTON -->
                <form method="POST" class="fav-btn">
                    <input type="hidden" name="pet_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="toggle_fav" class="btn btn-heart">
                    <i class="fa fa-heart text-danger"></i>
                    </button>
                </form>

                <a href="pet.php?id=<?php echo $row['id']; ?>" class="card-link">

              <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" style="height:350px; object-fit:cover;">

              <div class="card-body">
                <h5><?php echo $row['name']; ?></h5><?php if ($row['status'] === 'reserved'): ?>
            <span class="badge bg-warning text-dark">Reserved</span>
          <?php endif; ?>
                 
              </div>

            </div>

          </a>
        </div>

      <?php endwhile; ?>
    <?php else: ?>

      <p class="mt-3">You haven't saved any pets yet.</p>

    <?php endif; ?>

  </div>

</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
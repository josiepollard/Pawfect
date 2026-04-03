<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$message = "";

// GET USER DATA
$stmt = $conn->prepare("SELECT name, email, password FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();


// =======================
// UPDATE NAME
// =======================
if (isset($_POST['update_name'])) {

    $newName = $_POST['name'];

    $stmt = $conn->prepare("UPDATE users SET name=? WHERE id=?");
    $stmt->bind_param("si", $newName, $userId);

    if ($stmt->execute()) {
        $_SESSION['name'] = $newName;
        $message = "<div class='alert alert-success'>Name updated</div>";
    }
}


// =======================
// UPDATE PASSWORD
// =======================
if (isset($_POST['update_password'])) {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Check current password
    if (!password_verify($current, $user['password'])) {
        $message = "<div class='alert alert-danger'>Current password incorrect</div>";
    } elseif ($new !== $confirm) {
        $message = "<div class='alert alert-danger'>Passwords do not match</div>";
    } else {

        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $userId);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Password updated</div>";
        }
    }
}


// =======================
// DELETE ACCOUNT
// =======================
if (isset($_POST['delete_account'])) {

    // Optional: delete favourites + enquiries first
    $conn->query("DELETE FROM favourites WHERE user_id = $userId");

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
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


<div class="container mt-5">

  <h2>Account Settings</h2>

  <?php echo $message; ?>

  <!-- CHANGE NAME -->
  <div class="card p-4 mb-4">
    <h5>Update Name</h5>

    <form method="POST">
      <input type="text" name="name" class="form-control mb-2"
        value="<?php echo htmlspecialchars($user['name']); ?>" required>

      <button name="update_name" class="btn btn-updateName">Update Name</button>
    </form>
  </div>


  <!-- CHANGE PASSWORD -->
  <div class="card p-4 mb-4">
    <h5>Change Password</h5>

    <form method="POST">

      <input type="password" name="current_password" class="form-control mb-2"
        placeholder="Current Password" required>

      <input type="password" name="new_password" class="form-control mb-2"
        placeholder="New Password" required>

      <input type="password" name="confirm_password" class="form-control mb-2"
        placeholder="Confirm New Password" required>

      <button name="update_password" class="btn btn-updatePassword">Update Password</button>

    </form>
  </div>


  <!-- DELETE ACCOUNT -->
  <div class="card p-4 border-danger">
    <h5 class="text-danger">Danger Zone</h5>

    <form method="POST">
      <button name="delete_account" class="btn btn-deleteAcc"
        onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
        Delete Account
      </button>
    </form>
  </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top paw-navbar">
  <div class="container-fluid px-3 px-lg-4">

    <!-- Logo, clicking logo goes to homepage -->
    <a class="navbar-brand" href="index.php">
      <span ><i class="fa fa-paw" style="font-size:24px"></i> Pawfect Match </span>
    </a>

    <!-- menu button for smaller screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#toggleNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="toggleNav" >

        <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-lg-2 ">

            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="view_all.php">Our Animals</a></li>
           
            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
             

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<ul class="navbar-nav ms-auto">

<?php if (isset($_SESSION['user_id'])): ?>

  <!-- DROPDOWN -->
  <li class="nav-item dropdown">

    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
      Hi, <?php echo htmlspecialchars($_SESSION['name']); ?> 
    </a>

    <ul class="dropdown-menu dropdown-menu-end">


      <!-- Admin only -->
      <?php if ($_SESSION['role'] === 'admin'): ?>

        <li class="dropdown-header">Admin Tools</li>
        <li><a class="dropdown-item" href="add_pet.php">Add Pet</a></li>
        <li><a class="dropdown-item" href="enquiries.php">View Enquiries</a></li>
      <?php endif; ?>

      <?php if ($_SESSION['role'] === 'user'): ?>

       
        <li><a class="dropdown-item" href="#">Saved Pets</a></li>

      <?php endif; ?>

      <li><hr class="dropdown-divider"></li>

      <!-- Logout -->
      <li>
        <a class="dropdown-item text-danger" href="logout.php">
          Logout
        </a>
      </li>

    </ul>

  </li>

<?php else: ?>

  <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
  <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>

<?php endif; ?>

</ul>

            
        </ul>
      </div>
    </div>
  </div>
</nav>
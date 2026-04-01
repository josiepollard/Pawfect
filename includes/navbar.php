<nav class="navbar navbar-expand-lg navbar-dark sticky-top paw-navbar">
  <div class="container-fluid px-3 px-lg-4">

    <!-- Logo, clicking logo goes to homepage -->
    <a class="navbar-brand" href="#">
      <span ><i class="fa fa-paw" style="font-size:24px"></i> Pawfect Match </span>
    </a>

    <!-- menu button for smaller screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#toggleNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="toggleNav" >

        <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-lg-2 ">

            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

            <!-- Recipes dropdown -->
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="recipesDropdown" role="button" data-bs-toggle="dropdown">
                Our Animals
            </a>

            <!-- dropdown child links -->
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">All Animals</a></li>
                <li><a class="dropdown-item" href="#">Dogs</a></li>
                <li><a class="dropdown-item" href="#">Cats</a></li>
            </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
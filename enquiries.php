<?php
$conn = new mysqli("localhost", "root", "", "pawfect");

$result = $conn->query("
    SELECT enquiries.*, pets.name AS pet_name 
    FROM enquiries 
    JOIN pets ON enquiries.pet_id = pets.id 
    ORDER BY date_sent DESC
");
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

<div class="container mt-4">

  <h2>Adoption Enquiries</h2>

  <table class="table table-bordered mt-3">
    <tr>
      <th>Pet</th>
      <th>Name</th>
      <th>Email</th>
      <th>Message</th>
      <th>Date</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['pet_name']; ?></td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['email']; ?></td>
      <td><?php echo $row['message']; ?></td>
      <td><?php echo $row['date_sent']; ?></td>
    </tr>
    <?php endwhile; ?>

  </table>

</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
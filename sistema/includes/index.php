<?php
session_start();
if (empty($_SESSION['active'])) {
	header('location: ../');
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

<?php include "scripts.php";?>
	</script>
	<title>Sisteme Ventas</title>
</head>
<body>
<?php include "header.php"; ?>
	<section id="container">
		<h1>Bienvenido al sistema</h1>
	</section>
	<?php include "footer.php"; ?>
</body>
</html>

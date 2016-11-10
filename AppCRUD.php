<!DOCTYPE html>
<html>
<head>
	<title>Examen Final Prova Practica Modul 2</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
	<script type="text/javascript">
		function confirmDel(id){		
		  var agree = confirm("¿Realmente desea eliminar el registro con id = " + id + "?");
		  if (agree){
		  	return true;
		  } 
		  return false;
		}
		function montarRegistro(id, nombre, apellidos, fecha){
			document.getElementById("id").value = id;
			document.getElementById("nom").value = nombre;
			document.getElementById("cognom").value = apellidos;
			document.getElementById("datanaix").value = fecha;
		}
	</script>
</head>
<body>

<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "db_empl";
	$nombre = $apellidos = $fechaNacimiento = "";
	$nombreErr = $apellidosErr = $fechaNacimientoErr = "";
	$fechaNacimientoValida = "";
	$mensajeInsertOK = "";
	$mensajeDelete = "";
	$mensajeUpdatedOK = "";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {   
		$fechaNacimientoValida = preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['datanaix']);

		if (!empty($_POST['nom']) 
		&& !empty($_POST['cognom']) 
		&& $fechaNacimientoValida) {
			if (!$_POST['id']){
				$sql = "INSERT INTO empleados (nombre, apellidos, fechaNacimiento)
				VALUES ('".$_POST['nom']."', '".$_POST['cognom']."', '".$_POST['datanaix']."')";
				if ($conn->query($sql) === TRUE) {
					$mensajeInsertOK = "Se ha creado un nuego registro";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
			else{
				$sql = "UPDATE empleados SET nombre='".$_POST['nom']."', apellidos='".$_POST['cognom']."', fechaNacimiento='".$_POST['datanaix']."' WHERE id='".$_POST['id']."'";
				if ($conn->query($sql) === TRUE) {
				    $mensajeUpdatedOK = "Se ha actualizado el registro con id = ".$_POST['id'];
				} else {
				    echo "Error updating record: " . $conn->error;
				}
			}
		}
		else{
			if (empty($_POST["nom"])) {
			    $nombreErr = "El nombre es un campo obligatorio";
			}
			else{
				$nombre = $_POST['nom'];
			}
			if (empty($_POST["cognom"])) {
			    $apellidosErr = "El apellido es un campo obligatorio";
			}
			else{
				$apellidos = $_POST['cognom'];
			}
			if (!$fechaNacimientoValida) {
				$fechaNacimientoErr = "La fecha de nacimiento no es válida";
			}
		}
	}

	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if (isset($_GET['rowid'])) {
			$sql = "DELETE FROM empleados WHERE id = ".$_GET['rowid'];
			if ($conn->query($sql) === TRUE) {
			    $mensajeDelete = "Se ha eliminado el registro con id = ".$_GET['rowid'];
			} else {
			    echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
	}
?>
	<div class="w3-card-4">
		<div class="w3-container w3-brown">
		  <h2>Registre d'empleats</h2>
		</div>
		<form class="w3-container" method="POST" action="<?php echo ($_SERVER["PHP_SELF"]);?>">
			<p><input type="hidden" id="id" name="id"></p>
			<p>
			<label class="w3-label w3-text-brown"><b>Nom</b></label>
			<span class="w3-text-brown">* <?= $nombreErr;?></span>
			<input class="w3-input w3-border w3-sand" id="nom" name="nom" type="text" value="<?= $nombre ?>">
			</p>

			<p>
			<label class="w3-label w3-text-brown"><b>Cognom</b></label>
			<span class="w3-text-brown">* <?= $apellidosErr;?></span>
			<input class="w3-input w3-border w3-sand" id="cognom" name="cognom" type="text" value="<?= $apellidos ?>"></p>

			<p>
			<label class="w3-label w3-text-brown"><b>Data Naix.</b><i> (format: 2016-07-17)</i></label>
			<span class="w3-text-brown">* <?= $fechaNacimientoErr;?></span>
			<input class="w3-input w3-border w3-sand" id="datanaix" name="datanaix" type="text" value="<?= $fechaNacimiento ?>"></p>

			<p><input class="w3-btn w3-brown" type="submit" name="submit" value="Enregistrar"></p>
		</form>
	</div>
<?php
	$sql = "SELECT id, nombre, apellidos, fechaNacimiento 
			FROM empleados ORDER BY id DESC";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) { ?>
	    <div class="w3-container w3-responsive">
	    	<?php if($mensajeInsertOK) : ?>
	    	<div class="w3-container w3-green"><h4><?= $mensajeInsertOK ?></h4></div>
	    	<?php endif; ?>
	    	<?php if($mensajeDelete) : ?>
	    	<div class="w3-container w3-pale-red"><h4><?= $mensajeDelete ?></h4></div>
	    	<?php endif; ?>
	    	<?php if($mensajeUpdatedOK) : ?>
	    	<div class="w3-container w3-blue"><h4><?= $mensajeUpdatedOK ?></h4></div>
	    	<?php endif; ?>

	    	<table class="w3-table w3-bordered w3-striped w3-large">
			    <tr class="w3-light-grey">
			        <th>Id</th>
					<th>Nom</th>
					<th>Cognoms</th>
					<th>Data Naix.</th>
					<th></th>
					<th></th>
			    </tr>
	    		<?php while($row = $result->fetch_assoc()) : ?> 
			    <tr>
				    <td><?= $row["id"] ?></td>
				    <td><?= $row["nombre"] ?></td>
				    <td><?= $row["apellidos"] ?></td>
				    <td><?= $row["fechaNacimiento"] ?></td>
				    <td><a class="w3-btn w3-pale-red w3-border" onclick="return confirmDel(<?= $row['id'] ?>);" href="AppCRUD.php?rowid=<?= $row['id'] ?>">Eliminar</a></td>
				    <td><a class="w3-btn w3-blue w3-border" id="modificar" onclick='montarRegistro(<?= $row['id']; ?>, "<?= $row['nombre'] ?>", "<?= $row['apellidos'] ?>", "<?= $row['fechaNacimiento'] ?>")'>Modificar</a></td>
				</tr> 
	    		<?php endwhile ?>
	    	</table>
	    </div> 
	<?php
	} else {
		echo "No hay resultados que mostrar";
	}
	$conn->close();
?>

</body>
</html>
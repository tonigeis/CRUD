<?php
include 'Miembro.php';
// include and register Twig auto-loader
include 'Twig/Autoloader.php';
Twig_Autoloader::register();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_empl";
$miembros = array();

try {
  // define template directory location
  $loader = new Twig_Loader_Filesystem('templates');
  
  // initialize Twig environment
  $twig = new Twig_Environment($loader);
  
  // load template
  $template = $twig->loadTemplate('listado.tmpl');
  
  $conn = new mysqli($servername, $username, $password, $dbname);
  
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  } 

  if ($_SERVER["REQUEST_METHOD"] == "POST") {   
    $fechaNacimientoValida = preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$_POST['datanaix']);
    if (!empty($_POST['nom']) 
    && !empty($_POST['cognom']) 
    && $fechaNacimientoValida) {
      $sql = "INSERT INTO empleados (nombre, apellidos, fechaNacimiento)
              VALUES ('".$_POST['nom']."', '".$_POST['cognom']."', '".$_POST['datanaix']."')";
      if ($conn->query($sql) === TRUE) {
        $mensajeInsertOK = "Se ha creado un nuego registro";
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
  }

  $sql = "SELECT id, nombre, apellidos, fechaNacimiento 
          FROM empleados ORDER BY id DESC";
  $result = $conn->query($sql);

  if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $miembro = new Miembro($row["id"], $row["nombre"], $row["apellidos"], $row["fechaNacimiento"]);
      array_push($miembros, $miembro);
    }
  } else{
    echo "No hay resultados que mostrar";
  }
  $conn->close();

  // set template variables
  // render template
  echo $template->render(array (
    'miembros' => $miembros
  ));
  
} catch (Exception $e) {
  die ('ERROR: ' . $e->getMessage());
}
?>
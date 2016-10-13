 <?php require '../wp-config.php';
//variables
 $usr_id= $alias= $nombre_completo= $direccion= $procesoHC= $telefono= $celular =$operadora= $nombre_parentesco = $parentesco= $telf_parentesco = "";

$guardado= $info="";
$cont=0;


if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	$usr_id=$_POST["usr_id"];

	if(empty($_POST["nombre_completo"])){
		$nombre_completo="";
	}else{
		$nombre_completo = filter_var($_POST["nombre_completo"],FILTER_SANITIZE_STRING);
	}
	if(empty($_POST["nombre_parentesco"])){
		$nombre_parentesco="";
	}else{
		$nombre_parentesco=filter_var($_POST["nombre_parentesco"],FILTER_SANITIZE_STRING);
	}
	if(empty($_POST["alias"])){
		$alias="";
	}else {
	    $alias = filter_var($_POST["alias"],FILTER_SANITIZE_STRING);
	    
	  }

	if (empty($_POST["procesoHC"])) {
    	$procesoHC = "";
	} else {
	    $procesoHC = filter_var($_POST["procesoHC"],FILTER_SANITIZE_STRING);
	}

	if (empty($_POST["direccion"])) {
    	$direccion = "";
	} else {
	    $direccion = filter_var($_POST["direccion"],FILTER_SANITIZE_STRING);
	}

	if (empty($_POST["telefono"])) {
    	$telefono = "";
	}else{
		$telefono=$_POST["telefono"];
		    
	}

	if (empty($_POST["celular"])) {
    	$celular = "";
	}else{
			$celular=$_POST["celular"];
	}

	if (empty($_POST["operadora"])) {
	    $operadora = "";
	  } else {
	    $operadora = validacion_input($_POST["operadora"]);
	  }

	if (empty($_POST["telf_parentesco"])) {
    	$telf_parentesco = "";
	}else{
			$telf_parentesco=$_POST["telf_parentesco"];
	}
	if(empty($_POST["parentesco"])){
		$parentesco="";
	}else{
		$parentesco=filter_var($_POST["parentesco"],FILTER_SANITIZE_STRING);
	}
	$cont=1;
}

if($cont==1){
	$servername = DB_HOST;
	$username = DB_USER;
	$password = DB_PASSWORD;
	$dbname = DB_NAME;
	$sql="";
	try {
		    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		    // set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    // sql to create table
		    $sql = "UPDATE wp_users SET display_name='".$alias."', full_name='".$nombre_completo."', address='".$direccion."', processHC='".$procesoHC."', phone='".$telefono."', mobile_phone='".$celular."', operator='".$operadora."', parentesco_name='".$nombre_parentesco."', parentesco='".$parentesco."', telef_parentesco='".$telf_parentesco."' WHERE ID='".$usr_id."'";
		    
	    // preparar query
	    $stmt = $conn->prepare($sql);

	    // execute the query
	    $stmt->execute();
	   
	    }catch(PDOException $e)
	    {
	    	echo '<script type="text/javascript">
			alert("Uupps. Se produjo un error, sus datos no fueron guardados.");
			window.location.assign("/wordpressHC/index.php");
			</script>';
		    $cont=0;
		    
	    	
	    }
	//cerrar conexion
	$conn = null;
	$cont=0;
	echo '<script type="text/javascript">
	alert("Sus datos fueron guardados exitosamente.");
	window.location.assign("/wordpressHC/index.php");
	</script>';
}

function validacion_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
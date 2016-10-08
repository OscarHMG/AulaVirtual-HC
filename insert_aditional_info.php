<?php
//Credenciales
session_start();
$link = mysqli_connect("localhost", "root", "root","wordpresshc");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Get the values!
$full_name =$_POST['full_name'];
$address = $_POST['address'];
$process_hc= $_POST['process_hc'];
$phone = $_POST['phone'];
$mobile = $_POST['mobile'];
$operadora = $_POST['operadora'];
$full_name_contact = $_POST['full_name_contact'];
$parentesco = $_POST['parentesco'];
$mob_parentesco = $_POST['mob_parentesco'];
//Get the info in the sessions
$ci = '';
$ci = $_SESSION['ID'];


//Queries.
$fields = "(id_user, full_name, address,processHC,phone,mobile_phone,operator,parentesco_name,parentesco,telef_parentesco)";
$values = "('" .$ci."','".$full_name."','".$address."','".$process_hc."','".$phone."','".$mobile."','".$operadora."','".$full_name_contact."','".$parentesco."','".$mob_parentesco."')";

/*$sql = "INSERT INTO wp_user_info_additional
(id_user, full_name, address,processHC,phone,mobile_phone,operator,parentesco_name,parentesco,telef_parentesco) 
VALUES (32,'oscar moncayo','duran','pruebaHC','861740','0980408714','claro','Hernan Moncayo','papa','0984737679')"; */
$sql = "INSERT INTO wp_user_info_additional".$fields." VALUES".$values;
if($ci!=null){
	if(mysqli_query($link, $sql)){
		session_destroy();
		//echo "Registro Completo. Ahora vamos a ingresar a nuestra cuenta.";
		//header( "refresh:1;url=wp-login.php"); //CAMBIA URL 
		//header( "refresh:1;url=http://localhost/wordpressHC/wp-login.php"); //CAMBIA URL 
		header('Location:http://localhost/wordpressHC/wp-login.php');
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
 }
// Close connection
mysqli_close($link);
?>
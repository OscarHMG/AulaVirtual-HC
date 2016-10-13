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
$set_fields='';
$update='';
$ci = $_SESSION['ID'];

$set_fields="full_name='".$full_name."',"."address='".$address."',"."processHC='".$process_hc."',"."phone='".$phone."',"."mobile_phone='".$mobile."',"."operator='".$operadora."',"."parentesco_name='".$full_name_contact."',"."parentesco='".$parentesco."',"."telef_parentesco='".$mob_parentesco."'";
$update="UPDATE wp_users SET ".$set_fields." WHERE ID=".$ci;

if($ci!=null){
	if(mysqli_query($link, $update)){
		session_destroy();
		//echo "Registro Completo. Ahora vamos a ingresar a nuestra cuenta.";
		//header( "refresh:1;url=wp-login.php"); //CAMBIA URL 
		//header( "refresh:1;url=http://localhost/wordpressHC/wp-login.php"); //CAMBIA URL 
		header('Location:http://186.3.171.15//wordpressHC/wp-login.php');
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
 }
// Close connection
mysqli_close($link);
?>
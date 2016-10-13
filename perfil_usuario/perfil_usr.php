<!DOCTYPE html>
<html>
<header>
<style>
.error {color: #FF0000;
font-weight: bold;
font-style: oblique;}
.ok {color: #006738;;
font-weight: bold;
font-style: oblique;
font-size: 156%;}
</style>

 <link rel="stylesheet" href="style_usr.css">
 <link rel="stylesheet" href="responsive.css">
 
 <?php 
//variables
$user_login = $complete_name = $nickname = $grupohc = $address = $telefono= $celular = $operadora = $nombre_fam = $tel_familiar = $parentezco ="";
$complete_nameEr = $telefonoEr = $celularEr = $tel_familiarEr ="";

$guardado= $info="";
$cont=0;


if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	

	if(empty($_POST["complete_name"])){
		$complete_nameEr="Este campo es obligatorio";
	}else{
		$complete_name = filter_var($_POST["complete_name"],FILTER_SANITIZE_STRING);
	}
	if(empty($_POST["nombre_fam"])){
		$nombre_fam="";
	}else{
		$nombre_fam=filter_var($_POST["nombre_fam"],FILTER_SANITIZE_STRING);
	}
	if(empty($_POST["nickname"])){
		$nickname="";
	}else {
	    $nickname = filter_var($_POST["nickname"],FILTER_SANITIZE_STRING);
	    
	  }

	if (empty($_POST["grupohc"])) {
    	$grupohc = "";
	} else {
	    $grupohc = filter_var($_POST["grupohc"],FILTER_SANITIZE_STRING);
	}

	if (empty($_POST["address"])) {
    	$address = "";
	} else {
	    $address = filter_var($_POST["address"],FILTER_SANITIZE_STRING);
	}

	if (empty($_POST["telefono"])) {
    	$telefono = "";
	} else {
		$temp = preg_replace("/[^0-9]/","",$_POST["telefono"]);
		if (strlen($temp)>=7  && strlen($temp)<=9)
		    $telefono=$temp;
		else
			$telefono=$temp;
		    $telefonoEr = "Número de teléfono no válido, corrija o deje el campo vacío";
	}

	if (empty($_POST["celular"])) {
    	$celular = "";
	} else {
		$temp = preg_replace("/[^0-9]/","",$_POST["celular"]);
		if (strlen($temp) ==10)
		    $celular=$temp;
		else
			$celular=$temp;
		    $celularEr = "Número de celular no válido, corrija o deje el campo vacío";
	}

	if (empty($_POST["operadora"])) {
	    $operadora = "-Seleccione-";
	  } else {
	    $operadora = validacion_input($_POST["operadora"]);
	  }

	if (empty($_POST["tel_familiar"])) {
    	$tel_familiar = "";
	} else {
		$temp = preg_replace("/[^0-9]/","",$_POST["tel_familiar"]);
		if (strlen($temp)>=7  && strlen($temp)<=9 or strlen($temp) ==10)
		    $tel_familiar=$temp;
		else
			$tel_familiar=$temp;
		    $tel_familiarEr = "Número de teléfono no válido, corrija o deje el campo vacío";
	}
	if(empty($_POST["parentezco"])){
		$parentezco="";
	}else{
		$parentezco=filter_var($_POST["parentezco"],FILTER_SANITIZE_STRING);
	}

	$cont=1;
	
	

}

if ($complete_nameEr == "" &&  $telefonoEr ==""  && $celularEr =="" && $tel_familiarEr =="" && $cont==1){
	//Conexion base 
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "wordpresshc";

	try {
		    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		    // set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    // sql to create table
		    $sql = "";
		    //$conn->exec($sql);
		    $guardado="Se guardaron todos sus datos satisfactoriamente.<br>";
	    }
	catch(PDOException $e)
	    {
	    	if($cont ==1){
		    	$guardado="Sus datos no fueron guardados.<br>";
		    	$info = $sql . "<br>" . $e->getMessage();
		    	$cont=0;
	    	}
	    }
	//cerrar conexion
	$conn = null;
	$cont=0;
}else{
	if($cont ==1){
		$guardado="Sus datos no fueron guardados.<br>";
		$cont=0;
	}
}
function validacion_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
</header>
<body classs="home blog">
<div class="header-innerpage">
                <div class="container">
            <div class="logo">
                        <h1><a href="http://186.3.171.15//wordpressHC/">Aula Virtual Hogar de cristo</a></h1>
            </div><!-- logo -->
            <div class="toggle">
                <a class="toggleMenu" href="#" style="display: none;">Menu</a>
             </div><!-- toggle --> 
             <div class="sitenav" style="display: block;">                  
                    <div class="menu">
                    	<ul><li><a href="http://186.3.171.15//wordpressHC/">Aula Virtual</a>
                    	<li class="page_item page-item-499 current_page_item"><a href="http://186.3.171.15/wordpressHC/perfil/">Mi Perfil</a></li>
	                    </li><li class="page_item page-item-7"><a href="http://186.3.171.15/wordpressHC/wp-login.php?action=logout&_wpnonce=3ee0589ea5">Cerrar Sesión</a></li>

                </ul></div>
              </div><!-- nav --><div class="clear"></div>
        </div><!-- container -->
 </div>
 <div class="container">
<form id="usr_profile" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >

<h2 style="font-size: 244%;margin-left: 18px;margin-top: 35px;">Datos personales</h2>
<p><span class="error" style="margin-left: 6%;">* campos obligatorios.</span></p>

<table style="margin: 7%;">
	<tbody><tr class="user-user-login-wrap">
		<th><label for="user_login">Nombre de usuario</label></th>
		<td><input class="item" name="user_login" id="user_login" value="<?php echo $user_login;?>" disabled="disabled" type="text"> <span class="descripcion">El nombre de usuario no puede cambiarse.</span></td>
	</tr>


<tr >
	<th><label for="complete_name">Nombre Completo</label></th>
	<td><input class="item" name="complete_name" id="complete_name" value="<?php echo $complete_name;?>" type="text"><span class="error">* <?php echo $complete_nameEr; ?></span></td>
</tr>

<tr >
	<th><label for="nickname">Alias <span >(obligatorio)</span></label></th>
	<td><input class="item" name="nickname" id="nickname" value="<?php echo $nickname;?>" class="regular-text" type="text"></td>
</tr>

<tr >
	<th><label for="grupohc">Grupo de Hogar de Cristo al que pertenece</label></th>
	<td><input class="item" name="grupohc" id="grupohc" value="<?php echo $grupohc;?>"  type="text"></td>
</tr>

<tr >
	<th><label for="address">Dirección de Domicilio</label></th>
	<td><input class="item" name="address" id="address" value="<?php echo $address;?>" type="text"></td>
</tr>

<tr >
	<th><label for="telefono">Teléfono</label></th>
	<td><input class="item" name="telefono" id="telefono" value="<?php echo $telefono;?>" type="text" placeholder="(04)2123123"><span class="error"> <?php echo $telefonoEr; ?></span></td>
</tr>

<tr >
	<th><label for="celular">Celular</label></th>
	<td><input class="item" name="celular" id="celular" value="<?php echo $celular;?>" type="text" placeholder="0991232234"><span class="error" > <?php echo $celularEr; ?></span></td>
</tr>

<tr >
	<th><label for="operadora">Operadora</label></th>
	<td><select name="operadora" id="operadora">
					<option selected="<?php if (isset($operadora) && $operadora=="Claro") echo "selected";?>" >Claro</option>
					<option selected="<?php if (isset($operadora) && $operadora=="Movistar") echo "selected";?>">Movistar</option>
					<option selected="<?php if (isset($operadora) && $operadora=="Cnt") echo "selected";?>">Cnt</option>
					<option selected="<?php if (isset($operadora) && $operadora=="Otra") echo "selected";?>">Otra</option>
					<option selected="<?php if (isset($operadora) && $operadora=="-Seleccione-") echo "selected";?>">-Seleccione-</option>
				</select></td>
</tr>

</tbody></table>

<h2 style="font-size: 244%;margin-left: 18px;margin-top: 35px;">Información de contacto</h2>



<table style="margin: 7%;">
<tbody>
<tr class="user-user-login-wrap">
	<th><label for="nombre_fam">Nombre de Familiar</label></th>
	<td><input class="item" name="nombre_fam" id="nombre_fam" value="<?php echo $nombre_fam;?>" type="text" > <span class="descripcion">El nombre de una persona cercana a ud, para en caso de emergencia comunicarnos con el/ella.</span></td>
</tr>

<tr >
	<th><label for="tel_familiar">Teléfono/Celular de Familiar             </label></th>
	<td><input class="item" name="tel_familiar" id="tel_familiar" value="<?php echo $tel_familiar;?>" type="text" placeholder="(04)2123123 / 0991232234"><span class="error" > <?php echo $tel_familiarEr; ?></span></td>
</tr>

<tr >
	<th><label for="parentezco">Parentezco</label></th>
	<td><input class="item" name="parentezco" id="parentezco" value="<?php echo $parentezco;?>" type="text"></td>
</tr>

</tbody></table>
<h2 style="text-align: center;">Si toda la información esta correcta, por favor clic en el botón "Actualizar Perfil".</h2>
<span class="ok" style="margin-left: 6%;"><?php echo $guardado; ?></span>
<span class="error" style="margin-left: 6%;"><?php echo $info; ?></span>
<p style="text-align: center;"><input name="submit" id="submit" class="buttonA" value="Actualizar Perfil" type="submit" style="margin: 2%;"></p>
</form>
</div>

<div id="footer-wrapper">
    	<div class="container">
           <div class="cols-4 widget-column-2">            	
               <h5>¿Qué Hacemos?</h5>            	
				<p>Vivienda Social y Hábitat<br>
Economía Popular Solidaria<br>
Desarrollo Comunitario<br>
Pastoral<br>
Salud y Seguridad Alimentaria<br>
Aulas del Conocimiento<br>
Acuicultura y Agricultura Social<br>
Banco de Materiales<br>
Casa de Acogida<br>
Voluntariado</p>            	
              </div>     
                      
               <div class="cols-4 widget-column-3">
                   <h5>Síguenos</h5>  
                             	
					<div class="clear"></div>                
                  <div class="social-icons">
					                    <a href="https://www.facebook.com/HogardeCristoEcuador" target="_blank" class="fb" title="facebook"></a>                    
                                        <a href="https://twitter.com/HogardeCristoEc" target="_blank" class="tw" title="twitter"></a> 
                    <!--                    <a href="https://www.youtube.com/user/HogardeCristoGye1" target="_blank" class="gp" title="google-plus"></a>-->
                    <!--                    <a href="https://www.instagram.com/hogardecristoec/" target="_blank" class="in" title="Instagram"></a>-->
                                        <a href="https://www.instagram.com/hogardecristoec/" target="_blank" class="in" title="Instagram"></a>                  </div>   
                </div>
                
                <div class="cols-4 widget-column-4">
                   <h5>Contáctanos</h5> 
                   <p>Av. Casuarina. Coop. Sergio Toral, Mz. 101/Bloque 1,<br>Ecuador </p>
              <div class="phone-no"><strong>Teléfono:</strong> +593 4 390 4449 Ext. 101 <br>
             
           <strong> Email:</strong> <a href="mailto:soportehogarcristo@gmail.com">soportehogarcristo@gmail.com</a></div>
              
                   
                </div><!--end .widget-column-4-->
                
                
            <div class="clear"></div>
        </div><!--end .container-->
        
        <div class="copyright-wrapper">
        	<div class="container">
            	<div class="copyright-txt">© 2016 <a href="http://186.3.171.15//wordpressHC/">Hogar de Cristo</a>. All Rights Reserved</div>
                <div class="design-by">Theme by <a href="http://www.sktthemes.net" target="_blank">SKT Themes</a></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ya casi estamos listos!</title>
		<link rel="stylesheet" href="css_register.css">
		<SCRIPT language=Javascript>
		var count =0;
		function isNumberKey(evt){
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57)){
				return false;
			}else{
				count++;
				var numLetters =  document.getElementById('mobile').value;
				//alert('correcto');
				if(numLetters.length  == 10){
					document.getElementById("operadora").disabled = false;
				}else{
					document.getElementById("operadora").disabled = true;
				}
				return true;
		}
	}

		function isNumber(evt){
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			return true;
	}

		</SCRIPT>

	</head>
	<body>
		<div class="form-style-10">
			<h1>Registro casi completo!<span>Ayúdanos con un poco mas de información</span></h1>
			<form action="insert_aditional_info.php" method="post">
				<div class="section"><span>1</span>INFORMACIÓN ADICIONAL</div>
				<div class="inner-wrap">
					<input type="text" name="full_name" placeholder="Nombre Completo" required="required"/>
					<input type="text" name="address" placeholder="Dirección" required="required"/>
					<input type="text" name="process_hc" placeholder="Proceso en Hogar de Cristo" required="required"/>
					<input type="text" name="phone" placeholder="Teléfono Fijo" onkeypress="return isNumberKey(event)" maxlength="7"/>	
					<input type="text" id="mobile" name="mobile" placeholder="Móvil" onkeypress="return isNumberKey(event)" maxlength="10" size="10"/>
					<select id="operadora" name="operadora" disabled="disabled" >
						<option value="default">Seleccione su operadora.</option>
						<option value="claro">Claro</option>
						<option value="movistar">Movistar</option>
					</select>
							
				</div>
				<!--Contact Info -->
				<div class="section"><span>2</span>SU PERSONA DE CONFIANZA</div>
				<div class="inner-wrap">
					<input type="text" name="full_name_contact" placeholder="Nombre Completo" required="required"/>
					<input type="text" name="parentesco" placeholder="Parentesco"/ required="required">
					<input type="text" name="mob_parentesco" placeholder="Teléfono Móvil/Fijo" onkeypress="return isNumber(event)" required="required" maxlength="10"/>
				</div>
				<!-- Submit, save in DB the info. Then, redirect to home page.-->
				<div class="button-section">
					<input type="submit" value="¡Vamos a los Cursos!"/>
				</div>				
			</form>
		</div>
		<?php
			$ci = '';
			$ci = $_SESSION['ID'];
			//global $wpdb;
			//
			//
			//
			/*$full_name =$_POST['full_name'];
			$address = $_POST['address'];
			$process_hc= $_POST['process_hc'];
			$phone = $_POST['phone'];
			$mobile = $_POST['mobile'];
			$operadora = $_POST['operadora'];
			$full_name_contact = $_POST['full_name_contact'];
			$parentesco = $_POST['parentesco'];
			$mob_parentesco = $_POST['mob_parentesco'];
			//Creating query to insert.

$full_name =$_POST['full_name'];
$address = $_POST['address'];
$process_hc= $_POST['process_hc'];
$phone = $_POST['phone'];
$mobile = $_POST['mobile'];
$operadora = $_POST['operadora'];
$full_name_contact = $_POST['full_name_contact'];
$parentesco = $_POST['parentesco'];
$mob_parentesco = $_POST['mob_parentesco'];

			$wpdb->query( $wpdb->prepare(
    			"INSERT INTO test_table (id_user, full_name, address,process_HC,phone,mobile_phone,operator,parentesco_name,parentesco,telef_parentesco) VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
    			array($ci,'oscar moncayo','duran','pruebaHC','861740','0980408714','claro','Hernan Moncayo','papa','0984737679')
    			)); <input type="text" size="50" value="<?php echo $ci?>"/> */
		?>	
		<p id="message">Campos con (*) son obligatorios</p>
		<input type="text" size="50" value="<?php echo $ci?>"/>
	</body>
</html>
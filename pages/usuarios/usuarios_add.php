<?php
    // chequear si se llama directo al script.
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="../images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    if ($_SERVER['HTTP_REFERER'] == "")	{
        echo "<script type='text/javascript'>window.location.href='index.php?view=login&msg_login=5'</script>";
//        echo "<script type='text/javascript'>window.location.href='index.php'</script>";
        exit;
    }
    
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_POST[save])) {
        $cedula=$_POST['cedula'];
        $cedula_insert = preg_replace("/\s+/", "", $cedula);
        $cedula_insert = str_replace("-", "", $cedula_insert);
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $password1 = $_POST['confirm_password'];
        $nivel_acceso = $_POST['nivel_acceso'];
        $status = 1;
        $unidad = $_POST['cod_unidad'];
        $solicitar_punto = $_POST['solicitar_punto'];
        $recibir_orden = $_POST['recibir_orden'];
        
        
        $query="SELECT * FROM $sql_tabla WHERE usuario='$usuario'";
        $result = pg_query($query)or die(pg_last_error());
        $total_encontrados = pg_num_rows ($result);
        pg_free_result($result);
        
        if ($total_encontrados != 0){
                $error="Error";
                $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: El Usuario ya est&aacute; registrado.</font></h3></div>';	
        }else {	
            $query="SELECT * FROM $sql_tabla WHERE cedula_usuario='$cedula_insert'";
            $result = pg_query($query)or die(pg_last_error());
            $result_user = pg_fetch_array($result);
            pg_free_result($result);
            
            if ($result_user[0]) {
                $error="Error";
                $div_menssage='<div align="left"><h3 class="error"><font color="#CC0000" style="text-decoration:blink;">Error: Ya Existe un Registro con la misma C&eacute;dula: <font color="blue">'.$result[0].'</font>; por favor verifique los datos!</font></h3></div>';				
            }else {	
                $error="bien";	
                $usuario=stripslashes($usuario);
                $passmd5=md5($password);
                
                $query="SELECT insert_usuario('$cedula_insert','$nombre','$apellido','$usuario','$passmd5',$nivel_acceso, $status,$unidad,$solicitar_punto,$recibir_orden)";
                $result = pg_query($query)or die(pg_last_error());
                if(pg_affected_rows($result)){
                    $error="bien";
                }					
            }					
        }
        

    } //fin del add        
?>
<!-- sincronizar mensaje cuando de muestra al usuario -->
<?php if($div_menssage) { ?>					
	<script type="text/javascript">
		function ver_msg(){
                    Effect.Fade('msg');
		}  
		setTimeout ("ver_msg()", 5000); //tiempo de espera en milisegundos
	</script>
 <?php } ?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                USUARIOS
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos Registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=usuarios";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=usuarios" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div>

<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>C&eacute;dula Usuario</label>
                                <input autofocus="true" class="form-control" type="text" id="cedula" name="cedula" value="<?php if ($error!="") echo $cedula; ?>" maxlength="12" size="12"/>
                                <p class="help-block">La letra V o E deben ser en MAYUSCULA</p>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Nombres</label>
                                <input class="form-control" type="text" id="nombre"  name="nombre" value="<?php if ($error!="") echo $nombre; ?>"  maxlength="50" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Apellidos</label>
                                <input class="form-control" type="text" id="apellido"  name="apellido"  value="<?php if ($error!="") echo $apellido; ?>" maxlength="50" size="25"/>
                            </div>

                            <div class="form-group">
                                <label>Generar Punto de Cuenta</label>
                                <select class="form-control" id="solicitar_punto" name="solicitar_punto">
                                    <option value="0">Desactivar</option>
                                    <option value="1">Activar</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Recibir Ordenes</label>
                                <select class="form-control" id="recibir_orden" name="recibir_orden">
                                    <option value="0">Desactivar</option>
                                    <option value="1">Activar</option>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Unidad/Departamento</label>
                                <select id="cod_unidad" name="cod_unidad" size="0" class="form-control">
                                    <option value="">----</option>          
                                    <?php
                                        $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                        $consulta=pg_query("select * from unidades order by cod_unidad");
                                        while ($array_consulta=pg_fetch_array($consulta)) {
                                            if( $array_consulta[0]==$cod_unidad){
                                                echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                            }else{
                                                echo '<option value="'.$array_consulta[0].'">'.$array_consulta[2].'</option>';
                                            }
                                        }
                                        pg_free_result($consulta);
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Usuario</label>
                                <input type="text" id="usuario" name="usuario" value="<?php if ($error!="") echo $usuario; ?>" class="form-control" maxlength="50" size="20"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Password</label>
                                <input class="form-control" id="password" name="password" type="password" maxlength="30" size="20"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Confirmar Password</label>
                                <input class="form-control" type="password" id="password1" name="confirm_password" maxlength="30" size="20"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>Nivel de Acceso</label>
                                <select id="nivel_acceso" name="nivel_acceso" class="form-control" size="0" class="options">
                                    <option  value="">----</option> 
                                    <?php
                                        $consulta=pg_query("select * from niveles_acceso order by codigo_nivel");
                                        while ($array_consulta=pg_fetch_array($consulta)){
                                            if( $array_consulta[0]==$nivel_acceso){
                                                echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                            }else{
                                                echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                            }
                                            
                                        }
                                        pg_free_result($consulta);
                                    ?>
                                </select>
                            </div>
                            <input type="submit" class="btn btn-default" name="save" value="  Guardar  " >
                            <input class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=usuarios'" value="Cerrar" name="cerrar" />  
                        </div>
                    </form>
                </div>
            </div>
                
<?php }  ?>

        </div>
    </div>
</div>

<!-- Bootstrap Core JavaScript -->
<script src="vendor/js/jquery.js"></script>
<script src="vendor/maskedinput/jquery.maskedinput.js"></script>

<script type="text/javascript" >
    jQuery(function($) {
          $.mask.definitions['~']='[JEVGDCH]';
          //$('#fecha_nac').mask('99/99/9999');
          //$('#fecha_deposito').mask('99/99/9999');
          $('#telefono').mask('(9999)-9999999');
          $('#celular').mask('(9999)-9999999');
          $('#telefono_trabajo').mask('(9999)-9999999');
          $('#telefono_fax').mask('(9999)-9999999');
          $('#rif').mask('~-9999?9999-9',{placeholder:" "});
          $('#cedula').mask('~-9999?99999',{placeholder:" "});
          //$('#phoneext').mask("(999) 999-9999? x99999");
          //$("#tin").mask("99-9999999");
          //$("#ssn").mask("999-99-9999");
          //$("#product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("Ha escrito lo siguiente: "+this.val());}});
          //$("#eyescript").mask("~9.99 ~9.99 999");
          
       });  
</script>
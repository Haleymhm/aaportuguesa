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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
?> 

<?php 
    if (isset($_POST[save])) {   // Insertar Datos del formulario
        $siglas = $_POST['siglas'];
        $nombre = $_POST['nombre'];
        $responsable = $_POST['nombre_responsable'];
        $cargo = $_POST['cargo'];
        $direccion = $_POST['direccion'];
        $telefono1 = $_POST['telefono_oficina'];
        $telefono2 = $_POST['telefono_movil'];
        $email = $_POST['correo_electronico'];
        $horario = $_POST['horario'];
        $unidad_online = $_POST['unidad_online'];

        $query="insert into unidades (siglas_unidad,nombre_unidad,responsable_unidad,cargo_responsable,direccion_unidad,telefono_1,telefono_2,email_unidad,horario_unidad,status_unidad) values ('$siglas','$nombre','$responsable','$cargo','$direccion','$telefono1','$telefono2','$email','$horario',$unidad_online)";
        $result = pg_query($query)or die(pg_last_error());
        $error="bien";    
        
    }//fin del add        
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
                DEPARTAMENTO/UNIDAD
            </div>
                
<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
                        
            <div class="form-group" align="center"> 
                <h3 class="info">	
                    <font size="2">						
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=unidades";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=unidades" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div>

<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>SIGLAS</label>
                                <input class="form-control" type="text" id="siglas_unidad" name="siglas_unidad"/>
                            </div>

                            <div class="form-group">
                                <label>DEPARTAMENTO/UNIDAD</label>
                                <input class="form-control" type="text" id="nombre" name="nombre" maxlength="50" size="50"/>
                            </div>

                            <div class="form-group">
                                <label>Direcci&oacute;n</label>
                                <textarea class="form-control" name="direccion" id="direccion" cols="70" rows="3"><?php echo $direccion;?></textarea>
                            </div>

                            <div class="form-group">
                                <label>TELÉFONO DE OFICINA</label>
                                <input class="form-control" placeholder="(0257)-1234567" type="text" id="telefono_oficina" name="telefono_oficina" maxlength="15" size="12"/>
                            </div>

                            <div class="form-group">
                                <label>TELÉFONO DE MOVIL</label>
                                <input class="form-control" placeholder="(0414)-1234567" type="text" id="telefono_movil" name="telefono_movil" maxlength="15" size="12"/>
                            </div>

                            <div class="form-group">
                                <label>CORREO ELECTRONICO</label>
                                <input class="form-control" placeholder="minombre@ejemplo.com" type="text" id="correo_electronico" name="correo_electronico" maxlength="50" size="50"/>
                            </div>

                            <div class="form-group">
                                <label>HORARIO DE ATENCIÓN</label>
                                 <input class="form-control" type="text" id="horario" name="horario" maxlength="50" size="50"/>
                            </div>

                            <div class="form-group">
                                <label>NOMBRE DEL RESPONSABLE</label>
                                <input class="form-control" type="text" id="nombre_responsable" name="nombre_responsable" maxlength="50" size="50"/>
                            </div>

                            <div class="form-group">
                                <label>CARGO</label>
                                <input class="form-control" type="text" id="cargo" name="cargo" maxlength="50" size="50"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOTIFICAR SI QUIERE ESTA UNIDAD DISPONIBLE ON-LINE</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="unidad_online" name="unidad_online" value="1">ACTIVAR
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="unidad_online" name="unidad_online" value="0">DESACTIVAR
                                    </label>
                                </div>
                            </div>
                            <input type="submit" class="button" name="save" value="  Guardar  " >
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=unidades'" value="Cerrar" name="cerrar" /> 
                        </div>
                    </form>
                </div>
            </div> 
                
<?php }  ?>
        </div>
    </div>
</div>

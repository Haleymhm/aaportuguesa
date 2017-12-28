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
    if (isset($_POST[save])) {    // Insertar Datos del formulario
        $descripcion = trim($_POST['descripcion']);
        $siglas = trim($_POST['siglas']);
        $etramite = trim($_POST['etramite']);
        $tetramite = trim($_POST['tetramite']);
        
        // Consultamos si existe la descripcion
        $query = "SELECT * FROM estados_tramites WHERE descripcion_estado_tramite='$descripcion'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);
        pg_free_result($result);						

        if (!$resultado[0]) {
            $query="insert into estados_tramites (siglas_estado_tramite,descripcion_estado_tramite,estado_tramite,tipo_estado_tramite) values ('$siglas','$descripcion','$etramite','$tetramite')";
            $result = pg_query($query)or die(pg_last_error());
            if(pg_affected_rows($result)){
                $error="bien";
            }
        } else {
            $error="Error";
            $div_menssage='<div align="left">
                        <h3 class="error">
                            <font color="red" style="text-decoration:blink;">
                                Error: Ya Existe un Registro con la descripcion: <font color="blue">'.$descripcion.'</font>; por favor verifique los datos!
                            </font>
                        </h3>
                    </div>';	
        }
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
                INGRESAR ESTADOS DE LOS TRAMITES
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
                
            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2"> 					
                        <h1>Datos registrados con &eacute;xito</h1>
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=edos_tramites";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=edos_tramites" name="Continuar"> Continuar </a>]
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
                                <input  type="text" id="siglas" name="siglas" value="<?php if ($error!='') echo $siglas;?>" onkeyup="" class="form-control" size="5" maxlength="5"/>
                            </div>

                            <div class="form-group">
                                <label>ESTADO</label>
                                <input  type="text" id="etramite" name="etramite" value="<?php if ($error!='') echo $etramite;?>" onkeyup="" class="form-control" size="5" maxlength="5"/> 
                            </div>

                            <div class="form-group">
                                <label>DESCRIPCI&Oacute;N</label>
                                <textarea class="form-control" name="descripcion" id="descripcion" cols="70" rows="3"><?php if ($error!='') echo $descripcion;?></textarea> 
                            </div>
                                
                            <div class="form-group">
                                <label>TIPO DE ESTADO</label>
                                <select id="tetramite" name="tetramite" class="form-control" size="1">
                                    <?php
                                        if($error!="") {
                                            if($tetramite=="1") {
                                                echo '<option value="'.$tetramite.'" selected="selected">Asignado</option>';
                                            }elseif($tetramite=="2") {
                                                echo '<option value="'.$tetramite.'" selected="selected">Completado</option>';
                                            }else{
                                                echo '<option value="'.$tetramite.'" selected="selected">Cancelado</option>';
                                            }
                                        }                                                                                   
                                    ?>
                                    <option value="" >---</option>
                                    <option value="1">Asignado</option>
                                    <option value="2">Completado</option>           
                                    <option value="3">Cancelado</option>
                                </select>
                            </div>
                            <button type="submit" id="save" name="save" class="btn btn-default">Guardar</button>
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=empresa'" value="Cerrar" name="cerrar" />
                        <div>
                    </form>
                </div>
            </div>
<?php }  ?>
         </div>       
    </div>
</div>

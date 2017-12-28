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
    
    if (isset($_GET['id_punto'])){ // Recibir los Datos 
        $datos_modificar= $_GET['id_punto'];

        $query="SELECT * FROM punto_cuenta, usuarios WHERE punto_cuenta.cedula_usuario = usuarios.cedula_usuario and punto_cuenta.id_punto=$datos_modificar order by punto_cuenta.id_punto";
        $result = pg_query($query)or die(pg_last_error());
        $result_usuarios=pg_fetch_array($result);	
        pg_free_result($result);
    }

    if (isset($_POST[save])) {
        $id_punto = $_POST['id_punto'];
        $enviar=0;
        $error="bien";		

        $passmd5=md5($pass);

	$query="SELECT update_enviar_punto($id_punto,$enviar)";
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);
        
        $error="bien";
    }//fin del procedimiento modificar.
   
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
                    <?php echo $div_menssage;?>
                </font>
            </div>

            <div class="panel-heading">
                ENVIAR PUNTO DE CUENTA
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                         
                        <h1>Punto de Cuenta Enviado con &eacute;xito</h1> 
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=punto_cuenta";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=punto_cuenta" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div>

<?php	} else{ 	?>   <!-- Mostrar formulario Original -->

            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="id_punto" name="id_punto" value="<?php echo $result_usuarios[id_punto]; ?>" readonly="true" type="hidden"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>Nombres</label>
                                <input autofocus="true" value="<?php echo $result_usuarios[nombre_usuario];?> <?php echo " "?> <?php echo $result_usuarios[apellido_usuario]; ?>" class="form-control" readonly type="text" id="nombre" name="nombre" maxlength="50" size="25"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>ASUNTO</label>
                                <textarea class="form-control" readonly="true" name="asunto" id="asunto" cols="70" rows="3"><?php echo $result_usuarios[asunto]; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>SISTESIS</label>
                                <textarea class="form-control" name="sintesis" id="sintesis" readonly="true" cols="70" rows="3"><?php echo $result_usuarios[sintesis]; ?></textarea>
                            </div>
                            <input type="submit" class="btn btn-default" name="save" value="  Enviar  " >
                            <input class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=punto_cuenta'" value="Cerrar" name="cerrar" /> 
                        </div>	
                    </form>
                </div>
            </div>

<?php }  ?>	

        </div>
    </div>
</div> 
        
<script type="text/javascript">
	var dtabs=new ddtabcontent("divsG")
	dtabs.setpersist(true)
	dtabs.setselectedClassTarget("link") //"link" or "linkparent"
	dtabs.init()
</script>

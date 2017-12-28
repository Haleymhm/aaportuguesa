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
    if (isset($_GET['cod_tipo_solicitante'])){
    	$datos_modificar= $_GET['cod_tipo_solicitante'];	

	$query="SELECT * FROM tipo_solicitantes where cod_tipo_solicitante = $datos_modificar";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);  	
    }
?> 

<?php 
    if (isset($_POST[save])) {
    	$codigo = trim($_POST['cod_tipo_solicitante']);
    	$descripcion = trim($_POST['descripcion']);
    
	// Consultamos si existe la descripcion
        $query = "SELECT * FROM tipo_solicitantes WHERE cod_tipo_solicitante='$codigo'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado_tipo_solicitante=pg_fetch_array($result);
        pg_free_result($result);
        
        if($resultado_tipo_solicitante[descripcion_tipo_solicitante]!=$descripcion){
            // Consultamos si existe la descripcion
            $query = "SELECT * FROM tipo_solicitantes WHERE descripcion_tipo_solicitante='$descripcion'";
            $result = pg_query($query)or die(pg_last_error());
            $resultado_load=pg_fetch_array($result);
            pg_free_result($result);
            
            if (!$resultado_load[0]) {
                //se le hace el llamado a la funcion de editar
                $query="SELECT update_tipo_solicitante($codigo,'$descripcion')";
                $result = pg_query($query)or die(pg_last_error());
                $error="bien";
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
            
        }else{
            //se le hace el llamado a la funcion de editar
            $query="SELECT update_tipo_solicitante($codigo,'$descripcion')";
            $result = pg_query($query)or die(pg_last_error());
            $error="bien";
        }
        

        	   
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
                ACTUALIZAR TIPOS DE SOLICITANTES:
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=tipo_solicitantes";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=tipo_solicitantes" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div> 
                    
<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 

            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="cod_tipo_solicitante" name="cod_tipo_solicitante" value="<?php echo $resultado[cod_tipo_solicitante]; ?>" type="hidden"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>DESCRIPCIÓN DE LA CATEGORIA</label>
                                <input  type="text" id="descripcion" name="descripcion" value="<?php if ($error!="") echo $descripcion; else echo $resultado[descripcion_tipo_solicitante]; ?>" class="form-control" size="50" maxlength="50"/>
                            </div>      
                            <input type="submit" class="btn btn-default" name="save" value="  Guardar  " >
                            <input class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=tipo_solicitantes'" value="Cerrar" name="cerrar" />  
                        </div>
                    </form>
                </div>
            </div>
<?php }  ?>	
        </div>
    </div>
</div>

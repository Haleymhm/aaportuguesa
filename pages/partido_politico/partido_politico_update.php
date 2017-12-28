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
    if (isset($_GET['id_partido'])){
    	$datos_modificar= $_GET['id_partido'];	

	$query="SELECT * FROM ente_publico where id_ente = $datos_modificar";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);  	
    }
?> 

<?php 
    if (isset($_POST[save])) {
    	$id_ente = trim($_POST['id_ente']);
    	$ente_publico = trim($_POST['ente_publico']);
    
	// Consultamos si existe la descripcion
        $query = "SELECT * FROM ente_publico WHERE id_ente='$id_ente'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado_ente=pg_fetch_array($result);
        pg_free_result($result);
        
        if($resultado_ente[ente_publico]!=$ente_publico){
            // Consultamos si existe la descripcion
            $query = "SELECT * FROM ente_publico WHERE ente_publico='$ente_publico'";
            $result = pg_query($query)or die(pg_last_error());
            $resultado_load=pg_fetch_array($result);
            pg_free_result($result);
            
            if (!$resultado_load[0]) {
                //se le hace el llamado a la funcion de editar
                $query="SELECT update_ente_publico($id_ente,'$ente_publico')";
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
            $query="SELECT update_ente_publico($id_ente,'$ente_publico')";
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
                MODIFICAR ENTE PUBLICO
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=ente_publico";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=ente_publico" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div> 

<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 

            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="id_ente" name="id_ente" value="<?php if ($error!="") echo $id_ente; else echo $resultado[id_ente]; ?>" type="hidden"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>PARTIDO POLITICO</label>
                                <input  type="text" id="ente_publico" name="ente_publico" value="<?php if ($error!="") echo $ente_publico; else echo $resultado[ente_publico]; ?>" class="form-control" size="80" maxlength="80"/>
                            </div>
                            <input type="submit" class="button" name="save" value="  Guardar  " >
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=ente_publico'" value="Cerrar" name="cerrar" /> 

                        </div>	
                    </form>
                </div>
            </div>

<?php }  ?>	

        </div>
    </div>
</div> 
<?php //seccion de mensajes del sistema.
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

//seccion para recibir los datos y modificarlos.
if (isset($_GET['codigo_nivel'])){
    $datos_modificar= $_GET['codigo_nivel'];

    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    //se le hace el llamado a la funcion de insertar.	
    $datos_consulta = pg_query("SELECT * FROM niveles_acceso where codigo_nivel = $datos_modificar") or die("No se pudo realizar la consulta a la Base de datos");

    $resultados1=pg_fetch_array($datos_consulta);
    pg_free_result($datos_consulta);
    pg_close();
}

if (isset($_POST[save]))
{//se resive los datos a ser modificados
    $codigo_nivel = $_POST['codigo_nivel'];
    $nombre_nivel = $_POST['nombre_nivel'];

    $error="bien";	
    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    //se le hace el llamado a la funcion de insertar.	
    $inserta_registro = pg_query("SELECT update_nivel($codigo_nivel,'$nombre_nivel')") or die(pg_last_error());
    $result_insert=pg_fetch_array($inserta_registro);	
    $resultado_insert=$result_insert[0];

    pg_free_result($inserta_registro);
    //header ("Location: $pagina");
    pg_close();	//exit;	   
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
                ACTUALIZAR DATOS DE NIVEL DE ACCESO
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){  ?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=nivel_acceso";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=nivel_acceso" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div>

<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
            
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input class="inputbox" type="hidden" id="codigo_nivel" name="codigo_nivel" value="<?php echo $resultados1[codigo_nivel]; ?>"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>Descripci&oacute;n</label>
                                <input class="form-control" type="text" id="nombre_nivel" name="nombre_nivel" value="<?php echo $resultados1[nombre_nivel]; ?>" maxlength="45" size="45"/>
                            </div>
                            <input type="submit" class="btn btn-default" name="save" value="  Guardar  " >
                            <input class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=banco'" value="Cerrar" name="cerrar" />  
                        </div>
                    </form>
                </div>
            </div>

            <?php }  ?>	
 
        </div>	
    </div>
</div>
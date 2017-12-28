<?php //ENRUTTADOR
    $redir=$_SERVER['HTTP_REFERER']; // Ruta para redireccionar a la pagina que nos llamo
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $type=$_GET["view"];
    $pagina=$pag.'?view='.$view;

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_empresa password=$sql_pass");

//seccion para recibir los datos y borrar.
    if (isset($_GET['codigo_nivel'])){
	$datos_borrar= $_GET['codigo_nivel'];

	//se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
	require("conexion/aut_config.inc.php");
	$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

	$consulta = pg_query("SELECT * FROM niveles_acceso") or die(pg_last_error());
	$total_registros = pg_num_rows ($consulta);
	pg_free_result($consulta);
	
	if ($total_registros == 1){
            $error="malo";	
	}
	else{
            $error="bien";	
            unlink("logo_empresa/$datos_borrar");
            //se le hace el llamado a la funcion de insertar.	
            $result_borrar=pg_query("SELECT drop_nivel($datos_borrar)") or die(pg_last_error());
            pg_close();
	}
    }
?> 

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
            <div class="panel-heading">
                BORRAR NIVEL DE ACCESO
            </div>

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">
                        <?php
                            if ($error=="bien"){	
                                echo '<h1>Datos Eliminados con &eacute;xito</h1>';
                            }else{
                                echo '<font size="2" style="text-decoration:blink;">El Registro: <font color="blue">'.$datos_borrar.'</font>; no puede ser eliminado, contiene registros asociados.</font>';
                            }
                        ?>
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
        </div>
    </div>
</div>

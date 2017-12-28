<?php //ENRUTTADOR
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

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
    
if (isset($_GET['id_punto'])){
	$datos_borrar= $_GET['id_punto'];

	//se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
	require("conexion/aut_config.inc.php");
	$db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	
	
	if ($total_registros == 1){
		$error="malo";	
	}else{
            $error="bien";	
            $result_borrar=pg_query("SELECT drop_punto_cuenta('$datos_borrar')") or die(pg_last_error());
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
                BORRAR PUNTO DE CUENTA
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
                                window.location="?view=punto_cuenta";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=punto_cuenta" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div> 
        </div>        
    </div>
</div>

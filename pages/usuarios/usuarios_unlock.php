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
	
    //se le hace el llamado al archivo de conexion y luego se realiza el enlace.	
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	
?>

<?php //seccion para recibir los datos y borrar.
if (isset($_GET['cedula'])){
    $cedula= $_GET['cedula'];
    $status= $_GET['status'];

    if($status == 1) {
        $status_unlock = 0;
        $error="bien";	
        //se le hace el llamado a la funcion de insertar.	
        $result_lock=pg_query("SELECT unlock_usuario('$cedula',$status_unlock)") or die(pg_last_error());
        pg_close();	
    }
    else {
        $status_unlock = 1;
        $error="bien";	
        //se le hace el llamado a la funcion de insertar.	
        $result_lock=pg_query("SELECT unlock_usuario('$cedula',$status_unlock)") or die(pg_last_error());
        pg_close();
    }
}
?> 

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-heading">
                BLOQUEAR/DESBLOQUEAR USUARIO
            </div>

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">
                                <?php 
                                    if ($error=="bien") {
                                        echo '<h1>DATOS PROCESADOS CON  &Eacute;XITO!</h1>';
                                    }
                                    else  {
                                        echo '<h1>LOS DATOS NO PUEDEN SER PROCESADOS!</h1>';			
                                    }			
                                ?>
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
                </td>
            </tr>	
            
       </table>	
    </div>
</div>

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
    
    $server=$_SERVER['SERVER_NAME']; // nombre del servidor web
    $pag=$_SERVER['PHP_SELF'];  // el NOMBRE y ruta de esta misma p�ina.
    $view=$_GET["view"];	
    $pagina=$pag.'?view='.$view;

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

    if (isset($_GET[status])){
        $unidad = $_GET[unidad];
        $status = $_GET[status];
        $cod_tramite = $_GET[cod_tramite];

        if($status == 1) {
            $status_update = 0;
            $resultado=pg_query("UPDATE tramites SET status_tramite='$status_update' WHERE cod_tramite='$cod_tramite';") or die(pg_last_error());
            pg_close();	
            $error="bien";
        } else {
            $status_update = 1;
            $resultado=pg_query("UPDATE tramites SET status_tramite='$status_update' WHERE cod_tramite='$cod_tramite';") or die(pg_last_error());
            pg_close();	
            $error="bien";
        }
    }
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
                MODIFICAR STATUS DEL TRAMITE
            </div>

            <div align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Status del Tramite Modificado con &Eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=tramites&cod_unidad=<?php echo $unidad;?>";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=tramites&cod_unidad=<?php echo $unidad;?>" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div> 
        </div>
    </div>
</div>
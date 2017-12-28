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
    
    if (isset($_POST[save])){
        $fecha_ini=implode('-',array_reverse(explode('/',$_POST["fecha_ini"]))); 
        $fecha_fin=implode('-',array_reverse(explode('/',$_POST["fecha_fin"]))); 

        echo "<script type='text/javascript'>window.location.href='reportes/imprimir_censos.php?fecha_ini=$fecha_ini&fecha_fin=$fecha_fin'</script>";
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
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                ESTADISTICO DE TICKET POR FECHA
            </div>

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=solicitante_load_view<?php echo '&cedula_rif='.$cedula_rif;?>";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=solicitante_load_view<?php echo '&cedula_rif='.$cedula_rif;?>" name="Continuar"> Continuar </a>]
                    </font>							
                </h3>
            </div>

<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form id="QForm" name="QForm" method="POST" action="reportes/imprimir_estadistico_ticket_fecha.php" enctype="multipart/form-data" target="_Blank">
                        <div class="col-lg-12">
                            <h1>RANGO DE FECHA</h1>
                            <div class="form-group" autofocus="true">
                                <label>FECHA INICIO</label>
                                <input autofocus="true" class="form-control" name="fecha_ini" type="date"   id="fecha_ini"  size="10" maxlength="10" value="<?php echo implode('/',array_reverse(explode('-',$fecha_ini)));?>" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                            </div>
                            <div class="form-group" autofocus="true">
                                <label>FECHA FINAL</label>
                                <input class="form-control" name="fecha_fin" type="date"   id="fecha_fin"  size="10" maxlength="10" value="<?php echo implode('/',array_reverse(explode('-',$fecha_fin)));?>" onKeyPress="ue_formatofecha(this,'/',patron,true);"/>
                            </div>
                        </div>
                        <input type="submit" class="button" name="save" value="  REPORTE  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=home'" value="Cerrar" name="cerrar" /> 
                    </form>
                </div>
            </div>

<?php }  ?>

        </div>  
    </div>
</div> 
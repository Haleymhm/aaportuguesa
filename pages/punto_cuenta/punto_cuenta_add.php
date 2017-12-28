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

    //Conexion a la base de datos
    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");

if (isset($_POST[save])){
    $cedula_usuario = strtoupper($_POST['cedula_usuario']);
    $fecha_punto = $_POST['fecha_punto'];
    $asunto = $_POST['asunto'];
    $sintesis = $_POST['sintesis'];

    require("conexion/aut_config.inc.php");
    $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");	

    $query="insert into punto_cuenta (cedula_usuario,fecha_punto,asunto,sintesis) values ('$cedula_usuario','$fecha_punto','$asunto','$sintesis')";
    $result = pg_query($query)or die(pg_last_error());
    $result_insert=pg_fetch_array($result);
    pg_free_result($result);
    $error="bien"; 

}//fin del add        
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
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                INGRESAR PUNTO DE CUENTA: <?php echo $_SESSION['username']?>
            </div>
            
<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
            
            <div class="form-group" align="center"> 
                <h3 class="info">	
                    <font size="2">						
                        <h1>Datos registrados con &eacute;xito</h1>
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
            
<?php }else{ ?>   <!-- Mostrar formulario Original --> 
                    
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input class="form-control" value="<?php echo $_SESSION['id']?>" type="hidden" id="cedula_usuario" name="cedula_usuario"/>
                        <input class="form-control" value="<?php echo date('d/m/Y');?>" type="hidden" id="fecha_punto" name="fecha_punto"/>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>FECHA DE SOLICITUD</label>
                                <input class="form-control" value="<?php echo date('d/m/Y');?>" type="text" id="fecha" name="fecha" readonly/>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>ASUNTO</label>
                                <textarea class="form-control" name="asunto" id="asunto" cols="70" rows="3"><?php echo $direccion_empresa;?></textarea>
                            </div>

                            <div class="form-group">
                                <label>SISTESIS</label>
                                <textarea class="form-control" name="sintesis" id="sintesis" cols="70" rows="3"><?php echo $direccion_empresa;?></textarea>
                            </div>
                            <button type="submit" id="save" name="save" class="btn btn-default">enviar</button>
                            <input  class="btn btn-default" type="button" onclick="javascript:window.location.href='?view=punto_cuenta'" value="Cerrar" name="cerrar" />
                        </div>
                    </form>  
                </div>
            </div>

<?php } ?>

        </div> 
    </div> 
</div>
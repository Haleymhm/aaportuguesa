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
    if (isset($_POST[save])) {   // Insertar Datos del formulario
        $descripcion = trim($_POST['descripcion']);
        $status = ($_POST['status']);
        $cat_online = trim($_POST['cate_online']);
        
        // Consultamos si existe la descripcion
        $query = "SELECT * FROM categorias WHERE descripcion_categoria='$descripcion'";
        $result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);
        pg_free_result($result);						

        if (!$resultado[0]) {
            $query="insert into categorias (descripcion_categoria,status_categoria,status_categoria_online) values ('$descripcion','$status',$cat_online)";
            $result = pg_query($query)or die(pg_last_error());
            
            if(pg_affected_rows($result)){
                $error="bien";
            }
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
    }//fin del add        
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
                CATEGORIA
            </div>
                
<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->
                
            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2">                     
                        <h1>Datos registrados con &eacute;xito</h1>
                        <br />
                        <script type="text/javascript">
                            function redireccionar(){
                                window.location="?view=categorias";
                            }  
                            setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                        </script> 						
                        [<a href="?view=categorias" name="Continuar"> Continuar </a>]
                        </font>							
                </h3>
            </div>
                
<?php }else{ ?>   <!-- Mostrar formulario Original --> 
                
            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>DESCRIPCIÓN DE LA CATEGORIA</label>
                                <input  type="text" id="descripcion" name="descripcion" value="<?php if ($error!='') echo $descripcion;?>" class="form-control" size="50" maxlength="50"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>STATUS DE LA CATEGORIA</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="status" name="status" value="1">ACTIVAR
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="status" name="status" value="0">DESACTIVAR
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOTIFICAR SI QUIERE ESTA CATEGORIA DISPONIBLE ON-LINE</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="cate_online" name="cate_online" value="1">ACTIVAR
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" id="cate_online" name="cate_online" value="0">DESACTIVAR
                                    </label>
                                </div>
                            </div>			
                            <button type="submit" id="save" name="save" class="btn btn-default">Guardar</button>
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=categorias'" value="Cerrar" name="cerrar" />  
                        </div>
                    </form>
                </div>
            </div>
<?php }  ?>
        </div>
    </div>
</div>

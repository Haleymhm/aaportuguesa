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

    if (isset($_GET['cod_unidad'])){
    	$datos_modificar= $_GET['cod_unidad'];

	$query="SELECT * FROM unidades where cod_unidad = $datos_modificar";
    	$result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
        pg_free_result($result);
    }
    
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div align="center">
                <font color="red" style="text-decoration:blink;">
                    <?php $error_accion_ms[$error_cod]?>
                </font>
            </div>  

            <div class="panel-heading">
                DEPARTAMENTO/UNIDAD
            </div>

            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="cod_unidad" name="cod_unidad" value="<?php echo $resultado[cod_unidad]; ?>" type="hidden"/>
                        <div class="col-lg-12">
                            <div class="form-group" autofocus="true">
                                <label>SIGLAS</label>
                                <input class="form-control" type="text" id="siglas_unidad" name="siglas_unidad" value="<?php echo $resultado[siglas_unidad]; ?>" readonly/>
                            </div>

                            <div class="form-group">
                                <label>DEPARTAMENTO/UNIDAD</label>
                                <input class="form-control" type="text" id="nombre" name="nombre" maxlength="50" size="50" value="<?php echo $resultado[nombre_unidad]; ?>" readonly/>
                            </div>

                            <div class="form-group">
                                <label>Direcci&oacute;n</label>
                                <textarea class="form-control" name="direccion" id="direccion" cols="70" rows="3" readonly><?php echo $resultado[direccion_unidad]; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>TELÉFONO DE OFICINA</label>
                                <input class="form-control" placeholder="(0257)-1234567" type="text" id="telefono_oficina" name="telefono_oficina" maxlength="15" size="12" value="<?php echo $resultado[telefono_1]; ?>" readonly/>
                            </div>

                            <div class="form-group">
                                <label>TELÉFONO DE MOVIL</label>
                                <input class="form-control" placeholder="(0414)-1234567" type="text" id="telefono_movil" name="telefono_movil" maxlength="15" size="12" value="<?php echo $resultado[telefono_2]; ?>" readonly />
                            </div>

                            <div class="form-group">
                                <label>CORREO ELECTRONICO</label>
                                <input class="form-control" placeholder="minombre@ejemplo.com" type="text" id="correo_electronico" name="correo_electronico" maxlength="50" size="50" value="<?php echo $resultado[email_unidad]; ?>" readonly/>
                            </div>

                            <div class="form-group">
                                <label>HORARIO DE ATENCIÓN</label>
                                 <input class="form-control" type="text" id="horario" name="horario" maxlength="50" size="50" value="<?php echo $resultado[horario_unidad]; ?>" readonly/>
                            </div>

                            <div class="form-group">
                                <label>NOMBRE DEL RESPONSABLE</label>
                                <input class="form-control" type="text" id="nombre_responsable" name="nombre_responsable" maxlength="50" size="50" value="<?php echo $resultado[responsable_unidad]; ?>" readonly/>
                            </div>

                            <div class="form-group">
                                <label>CARGO</label>
                                <input class="form-control" type="text" id="cargo" name="cargo" maxlength="50" size="50" value="<?php echo $resultado[cargo_responsable]; ?>" readonly/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOTIFICAR SI QUIERE ESTA UNIDAD DISPONIBLE ON-LINE</label>
                                <div class="radio" readonly>
                                    <?php
                                        if($resultado[status_unidad]==1){
                                            echo '<div class="radio">
                                                <label>
                                                    <input type="radio" id="unidad_online" name="unidad_online" value="1" checked="true" readonly>ACTIVAR
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" id="unidad_online" name="unidad_online" value="0" readonly>DESACTIVAR
                                                </label>
                                            </div>';
                                        }else {
                                            echo '<div class="radio">
                                                <label>
                                                    <input type="radio" id="unidad_online" name="unidad_online" value="1" readonly>ACTIVAR
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" id="unidad_online" name="unidad_online" value="0" checked="true" readonly>DESACTIVAR
                                                </label>
                                            </div>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=unidades'" value="Cerrar" name="cerrar" /> 
                        </div>
                    </form>
                </div>
            </div>
       </div>    
    </div>
</div>
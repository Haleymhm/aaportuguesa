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

    if (isset($_GET['cod_tramite'])){
    	$datos_modificar= $_GET['cod_tramite'];
        
	$query="SELECT * FROM tramites,unidades where tramites.cod_unidad=unidades.cod_unidad AND tramites.cod_tramite = $datos_modificar";
    	$result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);	
    }

    if (isset($_POST[save])) {
    	$codigo = $_POST['ctramite'];
    	$categoria = $_POST['cod_categoria'];
    	$tramite = $_POST['tramite'];
    	$descripcion = $_POST['descripcion'];
        $unidad=$_POST['unidad'];
        $otorga = $_POST['otorga'];
        $costor = $_POST['costor'];
        $costoh = $_POST['costoh'];
        $entregar = $_POST['entregar'];
        $entregah = $_POST['entregah'];
        $horarioc = $_POST['horarioc'];
        $horarioe = $_POST['horarioe'];
        $observaciones = $_POST['observaciones'];
        $status_tramite = $_POST['status_tramite'];
        $status_tramite_online = $_POST['status_tramite_online'];
        
	//se le hace el llamado a la funcion  de editar
//	$query="SELECT update_tramite('$codigo','$categoria','$tramite','$descripcion','$unidad','$otorga','$costor','$costoh','$entregar','$entregah','$horarioc','$horarioe','$observaciones')";
	$query="UPDATE tramites SET cod_categoria=$categoria, nombre_tramite='$tramite', descripcion_tramite='$descripcion',cod_unidad='$unidad',cod_tipo_solicitante=$otorga,costo_regular='$costor',costo_habilitado='$costoh',entrega_regular='$entregar',entrega_habilitada='$entregah',horario_consignacion='$horarioc',horario_entrega='$horarioe',observaciones='$observaciones',status_tramite='$status_tramite',status_tramite_online=$status_tramite_online WHERE cod_tramite=$codigo;";
        $result = pg_query($query)or die(pg_last_error());
        pg_free_result($result);
        $error="bien";
        
        $query="SELECT * FROM tramites,unidades where tramites.cod_unidad=unidades.cod_unidad AND tramites.cod_tramite = $codigo";
    	$result = pg_query($query)or die(pg_last_error());
        $resultado=pg_fetch_array($result);
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
                ACTUALIZAR TRAMITES DEL DEPARTAMENTO/UNIDAD:
            </div>
        
            <div>
                <td width="100"><b>CODIGO:</b> <?php echo $resultado[cod_unidad]; ?></td> 
                <td width="120"><b>SIGLAS:</b> <?php echo $resultado['siglas_unidad']; ?></td>
                <td width="300"><b>NOMBRE:</b> <?php echo $resultado['nombre_unidad']; ?></td>
                <td><b>RESPONSABLE:</b> <?php echo $resultado['responsable_unidad']; ?></td>
            </div>	

<?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

            <div class="form-group" align="center"> 
                <h3 class="info">   
                    <font size="2"> 					
                        <h1>Datos registrados con &eacute;xito</h1> 
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
                   
<?php	}else{ 	?>   <!-- Mostrar formulario Original --> 

            <div class="panel-body">
                <div class="row">
                    <form method="POST" action="<?php echo $pagina?>" id="QForm" name="QForm" enctype="multipart/form-data">
                        <input id="ctramite" name="ctramite" value="<?php echo $resultado[cod_tramite]; ?>" type="hidden"/>
                        <input id="unidad" name="unidad" value="<?php echo $resultado[cod_unidad]; ?>" type="hidden"/>
                        <div class="col-lg-6">
                            <div class="form-group" autofocus="true">
                                <label>TRAMITE</label>
                                <input  type="text" id="tramite" autofocus="true" name="tramite" value="<?php echo $resultado['nombre_tramite'];?>" onkeyup="" class="form-control" size="50" maxlength="100"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>CATEGORIA DEL TRAMITE</label>
                                <select id="cod_categoria" name="cod_categoria" size="0" class="form-control">
                                    <option value="">----</option>          
                                        <?php
                                            
                                            $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                            $consulta=pg_query("select * from categorias order by cod_categoria");
                                            while ($array_consulta=pg_fetch_array($consulta)) {
                                                if ($resultado[cod_categoria]==$array_consulta[0]){
                                                 echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                }else{
                                                    echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                }
                                            }
                                            pg_free_result($consulta);
                                        ?>
                                </select>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>DESCRIPCIÓN DEL TRAMITE</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" cols="50" rows="3"><?php echo $resultado['descripcion_tramite'];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>SE OTORGA A</label>
                                <select id="otorga" name="otorga" size="0" class="form-control">
                                    <option value="">----</option>          
                                        <?php
                                            $db_conexion=pg_connect("host=$sql_host dbname=$sql_db user=$sql_usuario password=$sql_pass");
                                            $consulta=pg_query("select * from tipo_solicitantes order by cod_tipo_solicitante");
                                            while ($array_consulta=pg_fetch_array($consulta)) {
                                                 if ($resultado[cod_tipo_solicitante]==$array_consulta[0]){
                                                     echo '<option selected="selected" value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                    }else{
                                                        echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                    }
                                            }
                                            pg_free_result($consulta);
                                        ?>
                                </select>      
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>COSTO REGULAR (Bs.)</label>
                                <input  style="text-align:right" type="text" id="costor" class="form-control"  name="costor" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultado['costo_regular'];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>COSTO HABILITADO (Bs.)</label>
                                <input  style="text-align:right" type="text" id="costoh" class="form-control"  name="costoh" onKeyPress="return(ue_formatonumero(this,'','.',event));" maxlength="10" size="10" value="<?php echo $resultado['costo_habilitado'];?>" title="Ingrese el monto solicitado incluyendo los decimales. ej: 1300.00, el monto debe ser diferente de 0.00, El separador decimal es colocado automáticamente por el sistema"/>
                            </div>


                            <div class="form-group" autofocus="true">
                                <label>ENTREGA REGULAR (D&iacute;as)</label>
                                <input  type="text" id="entregar" name="entregar" value="<?php echo $resultado['entrega_regular'];?>" class="form-control" size="6" maxlength="8"/>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group" autofocus="true">
                                <label>ENTREGA HABILITADA (D&iacute;as)</label>
                                <input  type="text" id="entregah" name="entregah" value="<?php echo $resultado['entrega_habilitada'];?>" class="form-control" size="6" maxlength="8"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>HORARIO DE CONSIGNACIÓN</label>
                                <input  type="text" id="horarioc" name="horarioc" value="<?php echo $resultado['horario_consignacion'];?>" class="form-control" size="50" maxlength="200"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>HORARIO DE ENTREGA</label>
                                <input  type="text" id="horarioe" name="horarioe" value="<?php echo $resultado['horario_entrega'];?>" class="validate[required]" size="50" maxlength="200"/>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>OBSERVACIONES</label>
                                <textarea name="observaciones" id="observaciones" class="validate[required]" cols="50" rows="3"><?php echo $resultado['observaciones'];?></textarea>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOTIFICAR SI QUIERE ESTE TRAMITE ACTIVADO/DESACTIVADO</label>
                                <?php
                                    if($resultado[status_tramite]==1){
                                        echo '<div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite" name="status_tramite" value="1" checked="true">ACTIVAR
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite" name="status_tramite" value="0">DESACTIVAR
                                            </label>
                                        </div>';
                                    }else {
                                        echo '<div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite" name="status_tramite" value="1">ACTIVAR
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite" name="status_tramite" value="0" checked="true">DESACTIVAR
                                            </label>
                                        </div>';
                                    }
                                ?>
                            </div>

                            <div class="form-group" autofocus="true">
                                <label>NOTIFICAR SI QUIERE ESTE TRAMITE DISPONIBLE ON-LINE</label>
                                <?php
                                    if($resultado[status_tramite_online]==1){
                                        echo '<div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite_online" name="status_tramite_online" value="1" checked="true">ACTIVAR
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite_online" name="status_tramite_online" value="0">DESACTIVAR
                                            </label>
                                        </div>';
                                    }else {
                                        echo '<div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite_online" name="status_tramite_online" value="1">ACTIVAR
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" id="status_tramite_online" name="status_tramite_online" value="0" checked="true">DESACTIVAR
                                            </label>
                                        </div>';
                                    }
                                ?>
                            </div>  
                            <input type="submit" class="button" class="btn btn-default" name="save" value="  Guardar  " >
                            <input  class="button" type="button" onclick="javascript:window.location.href='?view=tramites&cod_unidad=<?php echo $resultado[cod_unidad];?>'" value="Cerrar" name="cerrar" />                                                
                        </div>
                    </form>
                </div>
            </div> 
                                
<?php }  ?>	

        </div>	 
    </div>
</div>
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
    if (isset($_GET['cedula_rif'])){ // Recibir los Datos 
        $cedula_rif= $_GET['cedula_rif'];

        $query="select * from solicitantes,comunidades where solicitantes.cedula_rif='$cedula_rif' AND solicitantes.idcom=comunidades.idcom";
        $result = pg_query($query)or die(pg_last_error());
        $result_solicitantes=pg_fetch_array($result);	
        pg_free_result($result);
    }
?> 

<?php 
    if (isset($_POST[save])){   // Insertar Datos del formulario
        $cedula_rif=$_POST['cedula_rif'];		
        $cod_tipo_solicitante=$_POST['cod_tipo_solicitante'];
        $nombreapellido=strtoupper($_POST["nombreapellido"]);
        $sexo=$_POST['sexo'];
        $fecha_nac=implode('-',array_reverse(explode('/',$_POST['fecha_nac']))); 
        $direccion=$_POST['direccion'];
        $telefono=$_POST['telefono'];
        $celular=$_POST['celular'];
        $email=$_POST['email'];
        $codcom=$_POST['codcom'];
        $empleado_publico=$_POST['empleado_publico'];
        $ente_publico=$_POST['ente_publico'];
        $miembro_partido=$_POST['miembro_partido'];
        $nombre_partido=$_POST['nombre_partido'];
        $profesion_solicitante=$_POST['profesion_solicitante'];
        $miembro_clp=$_POST['miembro_clp'];
        $miembro_ubch=$_POST['miembro_ubch'];
        $miembro_umujer=$_POST['miembro_umujer'];
        $miembro_francisco=$_POST['miembro_francisco'];
        $miembro_mincomuna=$_POST['miembro_mincomuna'];
        $pregonero=$_POST['pregonero'];
        
        $query="SELECT update_solicitante('$cedula_rif', '$cod_tipo_solicitante', '$nombreapellido', '$sexo', '$fecha_nac', '$direccion', '$telefono', '$celular', '$email', '$codcom', '$empleado_publico', '$ente_publico', '$miembro_partido', '$nombre_partido', '$profesion_solicitante', '$miembro_clp', '$miembro_ubch', '$miembro_umujer', '$miembro_francisco','$miembro_mincomuna', '$pregonero', 'now()')";
        $result = pg_query($query)or die(pg_last_error());
        $result_update=pg_fetch_array($result);
        pg_free_result($result);
        
        $error="bien";
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

<div align="center" class="centermain">
    <div class="main">  
        <table border="0" width="100%" align="center">
            <tbody>			
                <tr>
                    <td  id="msg" align="center">		
                        <?php echo $div_menssage;?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="adminclientes" width="100%">
            <tr>
                <th>
                    SOLICITANTE
                </th>
            </tr>
        </table>
	    					
	<form id="QForm" name="QForm" method="POST" action="<?php echo $pagina?>" enctype="multipart/form-data">
            <table class="adminform" border="0" width="100%">
                <tr>
                    <th colspan="2" align="center">
                        <img src="images/edit.png" width="16" height="16" alt="Editar Registro">
                        MODIFICAR DATOS DEL SOLICITANTE
                    </th>
                </tr>

                <?php if ((isset($_POST[save])) and ($error=="bien")){	?> <!-- Mostrar Mensaje -->

                <tr>
                    <td colspan="2" align="center">
                        <div align="center"> 
                            <h3 class="info">	
                                <font size="2">						
                                    Datos Modificados con &eacute;xito 
                                    <br />
                                    <script type="text/javascript">
                                        function redireccionar(){
                                            window.location="?view=solicitante_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>";
                                        }  
                                        setTimeout ("redireccionar()", 3000); //tiempo de espera en milisegundos
                                    </script> 						
                                    [<a href="?view=solicitante_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>" name="Continuar"> Continuar </a>]
                                </font>							
                            </h3>
                        </div> 
                    </td>
                </tr>

                <?php	}else{ 	?>   <!-- Mostrar formulario Original --> 
                
                <tr>
                   <td  colspan="2"   height="18">
                       <span> Los campos con <font color="Red" style="bold">(*)</font> son obligatorios</span>
                   </td>
                </tr>
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Solicitante:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td  width="20%" height="22">
                                            C&Eacute;DULA / RIF: <font color="Red">(*)</font>
                                    </td>
                                    <td  width="80%"  height="22">														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td width="100">									
                                                        <input type="hidden" id="cedula_rif" name="cedula_rif"  value="<?php echo $result_solicitantes['cedula_rif'];?>" />
                                                        <input size="10" class="inputbox validate[required]"  readonly="readonly" type="text" name="cedula1"  value="<?php  echo substr_replace($result_solicitantes['cedula_rif'],'-',1,0); ?>" /> 																																						
                                                    </td>
                                                    
                                                    <td>
                                                        TIPO DE SOLICITANTE: <font color="Red">(*)</font>
                                                    </td>
                                                    <td>														
                                                        <select id="cod_tipo_solicitante" name="cod_tipo_solicitante" class="validate[required]">	
                                                            <option value="">----</option>							
                                                            <?php 
                                                                $consulta_sql=pg_query("SELECT * FROM tipo_solicitantes");
                                                                while ($array_consulta=pg_fetch_array($consulta_sql)){
                                                                    if ($array_consulta[0]==$result_solicitantes['cod_tipo_solicitante']){
                                                                            echo '<option value="'.$array_consulta[0].'" selected="selected">'.$array_consulta[1].'</option>';
                                                                    }else {
                                                                            echo '<option value="'.$array_consulta[0].'">'.$array_consulta[1].'</option>';
                                                                    }
                                                                }
                                                                pg_free_result($consulta_sql);
                                                            ?>
                                                        </select>														
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>	

                                <tr>
                                    <td>
                                        NOMBRE DEL SOLICITANTE: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input class="validate[required] text-input" type="text" id="nombreapellido"  name="nombreapellido" value="<?php echo $result_solicitantes[nombre_solicitante];?>" onkeyup="" size="50" maxlength="50"/>																	
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                            SEXO: <font color="Red">(*)</font>
                                    </td>
                                    <td>														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <select id="sexo" name="sexo"  class="inputbox validate[required]" size="1">
                                                            <?php
                                                                if($result_solicitantes[sexo_solicitante]=="1") {
                                                                    echo '<option value="'.$result_solicitantes[sexo_solicitante].'" selected="selected">MASCULINO</option>';
                                                                }elseif($result_solicitantes[sexo_solicitante]=="2") {
                                                                    echo '<option value="'.$result_solicitantes[sexo_solicitante].'" selected="selected">FEMENINO</option>';
                                                                }else{
                                                                    echo '<option value="'.$result_solicitantes[sexo_solicitante].'" selected="selected">NO APLICA</option>';
                                                                }
                                                            ?>
                                                            <option value="" >---</option>
                                                            <option value="1">MASCULINO</option>
                                                            <option value="2">FEMENINO</option>																						
                                                            <option value="3">NO APLICA</option>																						
                                                        </select>														
                                                    </td>
                                                    <td>
                                                        FECHA NATAL: 
                                                        <input class="validate[custom[date],past[NOW]]"  name="fecha_nac" type="text" value="<?php echo implode('/',array_reverse(explode('-',$result_solicitantes['fecha_nac'])));?>"  id="fecha_nac"  size="10" maxlength="10" onKeyPress="ue_formatofecha(this,'/',patron,true);"  />
                                                        <img src="images/calendar.gif" title="Abrir Calendario..." alt="Abrir Calendario..." onclick="displayCalendar(document.forms[0].fecha_nac,'dd/mm/yyyy',this);">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>
                
                <tr>
                    <td class="titulo" colspan="2" height="18"  align="left"><b>Información del Ubicación:</b></td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td width="20%" >
                                        ESTADO: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <select id="codest" name="codest" class="validate[required]" onchange="cargarContenidoMunicipio();" onclick="cargarContenidoMunicipio();"  >
                                            <option value="">----</option>
                                            <?php 
                                                $consulta_sql=pg_query("SELECT * FROM estados order by codest") or die('La consulta fall&oacute;: ' . pg_last_error());
                                                while ($array_consulta=  pg_fetch_array($consulta_sql)){
                                                    if ($array_consulta[1]==$result_solicitantes[codest]){
                                                        echo '<option value="'.$array_consulta[1].'" selected="selected">'.$array_consulta[2].'</option>';
                                                    }else {
                                                        echo '<option value="'.$array_consulta[1].'">'.$array_consulta[2].'</option>';
                                                    }
                                                }
                                                pg_free_result($consulta_sql);
                                            ?>
                                        </select>
                                    </td>	
                                </tr>

                                <tr>
                                    <td width="15%" >
                                        MUNICIPIO: <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <div id="contenedor2">
                                            <select name="codmun" id="codmun" class="validate[required]" onChange="cargarContenidoParroquia();">
                                                <option value="">----</option>
                                                <?php										
                                                    $consultax1="SELECT * from municipios where codest='$result_solicitantes[codest]' order by codmun";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[2]==$result_solicitantes[codmun]){
                                                            echo '<option value="'.$vector[2].'" selected="selected">'.$vector[3].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[2].'">'.$vector[3].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);
                                                ?>
                                            </select>
                                        </div>
                                    </td>	
                                </tr>

                                <tr >
                                    <td width="15%" >
                                        PARROQUIA: <font color="Red">(*)</font>
                                    </td>
                                    <td>		
                                        <div id="contenedor3">
                                            <select name="codpar" id="codpar" class="validate[required]" onchange="cargarContenidoComunidad();" >
                                                <option value="">----</option>
                                                <?php 
                                                    $consultax1="SELECT * from parroquias where codest='$result_solicitantes[codest]' and codmun='$result_solicitantes[codmun]' order by codpar";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[3]==$result_solicitantes[codpar]){
                                                            echo '<option value="'.$vector[3].'" selected="selected">'.$vector[4].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[3].'">'.$vector[4].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);																		
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="comunidades">
                                    <td>
                                        COMUNIDAD: <font color="Red">(*)</font>
                                    </td>
                                    <td>		
                                        <div id="contenedor4">			
                                            <select name="codcom" id="codcom" class="validate[required]" style="width:180px" >
                                                <option value="">----</option>
                                                <?php 
                                                    $consultax1="SELECT * from comunidades where codest='$result_solicitantes[codest]' and codmun='$result_solicitantes[codmun]' and codpar='$result_solicitantes[codpar]'  order by descom";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[0]==$result_solicitantes[idcom]){
                                                            echo '<option value="'.$vector[0].'" selected="selected">'.$vector[5].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[0].'">'.$vector[5].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);																		
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                </tr>

                                
                                <tr>
                                    <td>
                                        DIRECCI&Oacute;N DE HABITACI&Oacute;N:  <font color="Red">(*)</font>
                                    </td>
                                    <td>
                                        <input class="validate[required] text-input" type="text" id="direccion" name="direccion" value="<?php echo $result_solicitantes[direccion_habitacion];?>"  size="60" maxlength="150"/>	
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        TEL&Eacute;FONO HAB.:
                                    </td>
                                    <td>														
                                        <table border="0" >
                                            <tbody>
                                                <tr>
                                                    <td width="130">
                                                        <input class="validate[custom[phone]] text-input" placeholder="(0212)-1234567" title="Ej.: (0212)-1234567" id="telefono" type="text" name="telefono" size="15" value="<?php echo $result_solicitantes[telefono_fijo];?>" maxlength="15"/>														
                                                    </td>
                                                    <td>
                                                        TEL&Eacute;FONO CEL.: <font color="Red">(*)</font>
                                                    </td>
                                                    <td>
                                                        <input class="validate[required,custom[phone]] text-input" placeholder="(0414)-1234567" title="Ej.: (0414)-1234567" id="celular" type="text" name="celular" size="15" value="<?php echo $result_solicitantes[telefono_movil];?>" maxlength="15"/>														
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        CORREO ELECTR&Oacute;NICO:
                                    </td>
                                    <td>
                                        <input class="validate[custom[email]] text-input" placeholder="minombre@ejemplo.com" title="Ej.: minombre@ejemplo.com" type="text" id="email" name="email" size="50" value="<?php echo $result_solicitantes[email];?>" maxlength="50"/>																		
                                    </td>
                                </tr>
                            </tbody>
                        </table>	
                    </td>
                </tr>

                            <tr>
                <td class="titulo" colspan="2" height="18"  align="left">
                    <b>Información Social:</b>
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                    <table class="borded" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                        <tr>
                            <td width="20%" >
                                EMPLEADO PUBLICO: <font color="Red">(*)</font>
                            </td>

                            <td>
                            <table border="0">
                                <tbody>
                                <tr>
                                    <td>
                                        <select id="empleado_publico" name="empleado_publico" class="validate[required]">
                                            <option value="<?php echo $result_solicitantes[empleado_publico];?>">
                                                <?php
                                                if ($result_solicitantes[empleado_publico]==1){ 
                                                    echo "SI";
                                                }else{
                                                    echo "NO";
                                                }?>
                                            </option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </td>

                                    <td>
                                        ENTE PUBLICO: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="ente_publico" id="ente_publico" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[ente_publico];?>"><?php echo $result_solicitantes[ente_publico];?></option>
                                                <option value="empresa_privada">EMPRESA PRIVADA</option>
                                                <?php                                       
                                                    $consultax1="SELECT * from ente_publico order by id_ente";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[0]==$id_ente){
                                                            echo '<option value="'.$vector[1].'" selected="selected">'.$vector[1].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[1].'">'.$vector[1].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);
                                                ?>
                                            </select>
                                        </div>
                                    </td>  

                                    <td>
                                        PROFESION: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="profesion_solicitante" id="profesion_solicitante" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[nombre_profesion];?>"><?php echo $result_solicitantes[nombre_profesion];?></option>
                                                <?php                                       
                                                    $consultax1="SELECT * from profesion order by id_profesion";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[0]==$id_ente){
                                                            echo '<option value="'.$vector[1].'" selected="selected">'.$vector[1].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[1].'">'.$vector[1].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);
                                                ?>
                                            </select>
                                        </div>
                                    </td> 
                                </tr>
                                </tbody>
                            </table>
                            </td>   
                        </tr>

                        <tr>
                            <td width="20%" >
                                MIEMBRO DE PARTIDO POLITICO: <font color="Red">(*)</font>
                            </td>

                            <td>
                            <table border="0">
                                <tbody>
                                <tr>
                                    <td>
                                        <select id="miembro_partido" name="miembro_partido" class="validate[required]">
                                            <option value="<?php echo $result_solicitantes[miembro_partido];?>">
                                                <?php
                                                if ($result_solicitantes[miembro_partido]==1){ 
                                                    echo "SI";
                                                }else{
                                                    echo "NO";
                                                }?>
                                            </option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </td>

                                    <td>
                                        NOMBRE DEL PARTIDO POLITICO: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="nombre_partido" id="nombre_partido" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[nombre_partido];?>"><?php echo $result_solicitantes[nombre_partido];?></option>
                                                <?php                                       
                                                    $consultax1="SELECT * from partido_politico order by id_partido";
                                                    $ejec_consultax1=pg_query($consultax1);
                                                    while($vector=pg_fetch_array($ejec_consultax1)){
                                                        if ($vector[0]==$id_ente){
                                                            echo '<option value="'.$vector[1].'" selected="selected">'.$vector[1].'</option>';
                                                        }else {
                                                            echo '<option value="'.$vector[1].'">'.$vector[1].'</option>';
                                                        }
                                                    }
                                                    pg_free_result($ejec_consultax1);
                                                ?>
                                            </select>
                                        </div>
                                    </td>  
                                </tr>
                                </tbody>
                            </table>
                            </td>   
                        </tr>

                        <tr>
                            <td width="20%" >
                                JEFE DE CLP: <font color="Red">(*)</font>
                            </td>

                            <td>
                            <table border="0">
                                <tbody>
                                <tr>
                                    <td>
                                        <select id="miembro_clp" name="miembro_clp" class="validate[required]">
                                            <option value="<?php echo $result_solicitantes[miembro_clp];?>">
                                            <?php
                                                if ($result_solicitantes[miembro_clp]==1){ 
                                                    echo "SI";
                                                }else{
                                                    echo "NO";
                                                }?>
                                            </option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </td>

                                    <td>
                                        MIEMBRO DE UBCH: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="miembro_ubch" id="miembro_ubch" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[miembro_ubch];?>">
                                                    <?php
                                                    if ($result_solicitantes[miembro_ubch]==1){ 
                                                        echo "SI";
                                                    }else{
                                                        echo "NO";
                                                    }?>
                                                </option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </td> 

                                    <td>
                                        MIEMBRO DE UNAMUJER: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="miembro_umujer" id="miembro_umujer" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[miembro_umujer];?>">
                                                    <?php
                                                    if ($result_solicitantes[miembro_umujer]==1){ 
                                                        echo "SI";
                                                    }else{
                                                        echo "NO";
                                                    }?>
                                                </option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </td> 

                                    <td>
                                        MIEMBRO FRENTE FRANCISCO MIRANDA: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="miembro_francisco" id="miembro_francisco" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[miembro_francisco];?>">
                                                    <?php
                                                    if ($result_solicitantes[miembro_francisco]==1){ 
                                                        echo "SI";
                                                    }else{
                                                        echo "NO";
                                                    }?>
                                                </option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </td>

                                    <td>
                                        MIEMBRO MINCOMUNA: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="miembro_mincomuna" id="miembro_mincomuna" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[miembro_mincomuna];?>">
                                                    <?php
                                                    if ($result_solicitantes[miembro_francisco]==1){ 
                                                        echo "SI";
                                                    }else{
                                                        echo "NO";
                                                    }?>
                                                </option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </td>

                                    <td>
                                        PREGONERO: <font color="Red">(*)</font>
                                    </td>

                                    <td>
                                        <div id="contenedor2">
                                            <select name="pregonero" id="pregonero" class="validate[required]"">
                                                <option value="<?php echo $result_solicitantes[pregonero];?>">
                                                    <?php
                                                    if ($result_solicitantes[miembro_francisco]==1){ 
                                                        echo "SI";
                                                    }else{
                                                        echo "NO";
                                                    }?>
                                                </option>
                                                <option value="1">SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                    </td> 
                                </tr>
                                </tbody>
                            </table>
                            </td>   
                        </tr>
                        </tbody>
                    </table>    
                </td>
            </tr>
                
                <tr>
                    <td colspan="2" class="botones" align="center" >			
                        <input type="submit" class="button" name="save" value="  Guardar  " >
                        <input  class="button" type="button" onclick="javascript:window.location.href='?view=solicitante_load_view<?php echo '&cedula_rif='.substr_replace($cedula_rif,'-',1,0);?>'" value="Cerrar" name="cerrar" />  
                    </td>													
                </tr> 
            <?php }  ?>	
        </table>
    </form>     
    <br>	 
    </div>
</div> 
        
<script type="text/javascript">
	var dtabs=new ddtabcontent("divsG")
	dtabs.setpersist(true)
	dtabs.setselectedClassTarget("link") //"link" or "linkparent"
	dtabs.init()
</script>		

<script type="text/javascript" >
	jQuery(function($) {
	      $.mask.definitions['~']='[JEVGDCHjevgdch]';
	      //$('#fecha_nac').mask('99/99/9999');
      
	      $('#telefono').mask('(9999)-9999999');
	      $('#celular').mask('(9999)-9999999');
	      
	});
</script>


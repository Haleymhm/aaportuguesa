<?php 
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }
    switch ($_SESSION['nivel']){
        			
        case 0: //Administrador Genral
            include("pages/menu/menu_super_root.php");
            break;

        case 1: //Administrador Genral
            include("pages/menu/menu_administrador_general.php");
            break;

        case 2: //Administrador Unidad
            include("pages/menu/menu_administrador_unidad.php");
            break;

        case 3: //Usuario Genral Unidad
            include("pages/menu/menu_usuario_general_unidad.php");
            break;

        case 4: //Administrador Atencion
            include("pages/menu/menu_administrador_atencion.php");
            break;

        case 5: //Administrador Atencion
            include("pages/menu/menu_analista_atencion.php");
            break;  

        case 6: //Administrador Atencion
            include("pages/menu/menu_ciudadano.php");
            break; 

        case 7: //Administrador Atencion
            include("pages/menu/menu_cconsulta.php");
            break; 
    }	
?>
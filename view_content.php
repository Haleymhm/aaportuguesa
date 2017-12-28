<?php
    if(!defined('INCLUDE_CHECK')){
        echo ('<div align="center"><img  src="images/acceso.png" width="237" height="206"/> <br /> No est&aacute; autorizado para realizar esta acci&oacute;n o entrar en esta P&aacute;gina </div>');
        //die('Usted no está autorizado a ejecutar este archivo directamente');
        exit;
    }

    switch ($view){
        // SECCION DEL SISTEMA
        CASE "home": 
            include("pages/home.php");
            break;
        CASE "login": 
            include("pages/index.php");
            break;			
        CASE "logoff": 
            include("pages/aut_logoff.php");
            break;

// SECCION DE DATOS DE LA EMPRESA Y DE CONFIGURACION
        CASE "empresa":
            include ("pages/empresa/aut_gestion_empresa.php");
            break;
        CASE "empresa_add":
            include ("pages/empresa/empresa_add.php");
            break;
        CASE "empresa_update":
            include ("pages/empresa/empresa_update.php");
            break;
        CASE "empresa_drop":
            include ("pages/empresa/empresa_drop.php");
            break;

// SECCION DE DATOS DEL NIVEL
        CASE "nivel_acceso":
            include ("pages/nivel_acceso/aut_gestion_nivel_acceso.php");
            break;
        CASE "nivel_acceso_add":
            include ("pages/nivel_acceso/nivel_acceso_add.php");
            break;
        CASE "nivel_acceso_update":
            include ("pages/nivel_acceso/nivel_acceso_update.php");
            break;
        CASE "nivel_acceso_drop":
            include ("pages/nivel_acceso/nivel_acceso_drop.php");
            break;

///////////// USUARIOS /////////////////        
        CASE "usuarios": 
            include("pages/usuarios/aut_gestion_usuario.php");
            break;
        CASE "usuarios_add": 
            include("pages/usuarios/usuarios_add.php");
            break;
        CASE "usuarios_update": 
            include("pages/usuarios/usuarios_update.php");
            break;
        CASE "usuarios_update_clave": 
            include("pages/usuarios/usuarios_update_clave.php");
            break;  
        CASE "usuarios_update_nivel": 
            include("pages/usuarios/usuarios_update_nivel.php");
            break;
        CASE "usuarios_drop": 
            include("pages/usuarios/usuarios_drop.php");
            break;
        CASE "usuarios_unlock": 
            include("pages/usuarios/usuarios_unlock.php");
            break;
        CASE "usuarios_update_perfil": 
            include("pages/usuarios/usuarios_update_perfil.php");
            break;
        CASE "usuarios_update_perfil_clave": 
            include("pages/usuarios/usuarios_update_perfil_clave.php");
            break;

///////////// ESTADOS DE TRAMITES /////////////////     
        CASE "edos_tramites": 
            include("pages/edos_tramites/aut_gestion_edos_tramites.php");
            break;
        CASE "edo_tramite_add": 
            include("pages/edos_tramites/edo_tramite_add.php");
            break;
        CASE "edo_tramite_update": 
            include("pages/edos_tramites/edo_tramite_update.php");
            break;
        CASE "edo_tramite_drop": 
            include("pages/edos_tramites/edo_tramite_drop.php");
            break;

        ///////////// CATEGORIAS /////////////////      
        CASE "categorias": 
            include("pages/categorias/aut_gestion_categorias.php");
            break;
        CASE "categoria_add": 
            include("pages/categorias/categoria_add.php");
            break;
        CASE "categoria_update": 
            include("pages/categorias/categoria_update.php");
            break;
        CASE "categoria_drop": 
            include("pages/categorias/categoria_drop.php");
            break;
        CASE "categoria_status": 
            include("pages/categorias/categoria_status.php");
            break;
    
///////////// DEPENDENCIAS /////////////////        
        CASE "unidades": 
            include("pages/unidades/aut_gestion_unidades.php");
            break;
        CASE "unidad_add": 
            include("pages/unidades/unidad_add.php");
            break;
        CASE "unidad_update": 
            include("pages/unidades/unidad_update.php");
            break;
        CASE "unidad_drop": 
            include("pages/unidades/unidad_drop.php");
            break;
        CASE "unidad_ver": 
            include("pages/unidades/unidad_ver.php");
            break;
        CASE "unidad_status": 
            include("pages/unidades/unidad_status.php");
            break;

///////////// TRAMITES /////////////////        
        CASE "tramites": 
            include("pages/tramites/aut_gestion_tramites.php");
            break;
        CASE "tramite_add":
            include("pages/tramites/tramite_add.php");
            break;
        CASE "tramite_update": 
            include("pages/tramites/tramite_update.php");
            break;
        CASE "tramite_drop": 
            include("pages/tramites/tramite_drop.php");
            break;
        CASE "tramite_status": 
            include("pages/tramites/tramite_status.php");
            break;

///////////// TIPOS DE SOLICITANTES /////////////////       
        CASE "tipo_solicitantes": 
            include("pages/tipo_solicitantes/aut_gestion_tipo_solicitantes.php");
            break;
        CASE "tipo_solicitante_add": 
            include("pages/tipo_solicitantes/tipo_solicitante_add.php");
            break;
        CASE "tipo_solicitante_update": 
            include("pages/tipo_solicitantes/tipo_solicitante_update.php");
            break;
        CASE "tipo_solicitante_drop": 
            include("pages/tipo_solicitantes/tipo_solicitante_drop.php");
            break;

///////////// COMUNIDADES /////////////////     
        CASE "comunidades": 
            include("pages/comunidades/aut_gestion_comunidades.php");
            break;
        CASE "comunidad_add": 
            include("pages/comunidades/comunidad_add.php");
            break;
        CASE "comunidad_update": 
            include("pages/comunidades/comunidad_update.php");
            break;
        CASE "comunidad_drop": 
            include("pages/comunidades/comunidad_drop.php");
            break;

//MODULO DEL PROFESION SOLICITANTE
        CASE "profesion_solicitante":
            include("pages/profesion_solicitante/aut_gestion_profesion_solicitante.php");
            break;  
        CASE "profesion_solicitante_add":
            include("pages/profesion_solicitante/profesion_solicitante_add.php");
            break;
        CASE "profesion_solicitante_update":
            include("pages/profesion_solicitante/profesion_solicitante_update.php");
            break;
        CASE "profesion_solicitante_drop":
            include("pages/profesion_solicitante/profesion_solicitante_drop.php");
            break;

//MODULO DEL ENTE PUBLICO
        CASE "ente_publico":
            include("pages/ente_publico/aut_gestion_ente_publico.php");
            break;  
        CASE "ente_publico_add":
            include("pages/ente_publico/ente_publico_add.php");
            break;
        CASE "ente_publico_update":
            include("pages/ente_publico/ente_publico_update.php");
            break;
        CASE "ente_publico_drop":
            include("pages/ente_publico/ente_publico_drop.php");
            break; 

//MODULO DEL PARTIDO POLITICO
        CASE "partido_politico":
            include("pages/partido_politico/aut_gestion_partido_politico.php");
            break;  
        CASE "partido_politico_add":
            include("pages/partido_politico/partido_politico_add.php");
            break;
        CASE "partido_politico_update":
            include("pages/partido_politico/partido_politico_update.php");
            break;
        CASE "partido_politico_drop":
            include("pages/partido_politico/partido_politico_drop.php");
            break;

///////////// TICKETS /////////////////     
        CASE "gestion_tickets_load": 
            include("pages/ticket/aut_gestion_tickets.php");
            break;
        CASE "gestion_tickets_prioridad": 
            include("pages/ticket/aut_gestion_tickets_prioridad.php");
            break;
        CASE "tickets": 
            include("pages/ticket/ticket_load_add.php");
            break;
        CASE "ticket_add": 
            include("pages/ticket/ticket_add.php");
            break;
        CASE "ticket_update": 
            include("pages/ticket/ticket_update.php");
            break;
        CASE "gestion_tickets":
            include("pages/ticket/ticket_load_view.php");
            break;
        CASE "ticket_status_update":
            include("pages/ticket/ticket_status_update.php");
            break;
        CASE "ticket_status_reprogramar":
            include("pages/ticket/ticket_status_reprogramar.php");
            break;
        CASE "ticket_status_escalar":
            include("pages/ticket/ticket_status_escalar.php");
            break;
        CASE "ticket_status_completar":
            include("pages/ticket/ticket_status_completar.php");
            break;
        CASE "ticket_status_cancelar":
            include("pages/ticket/ticket_status_cancelar.php");
            break;
        CASE "ticket_status_anular":
            include("pages/ticket/ticket_status_anular.php");
            break;
        CASE "ticket_detalle_view":
            include("pages/ticket/ticket_detalle_view.php");
            break;

///////////// SOLICITANTES /////////////////        
        CASE "solicitante_load_view": 
            include("pages/solicitantes/solicitante_load_view.php");
            break;
        CASE "solicitantes": 
            include("pages/solicitantes/aut_gestion_solicitantes.php");
            break;
        CASE "solicitante_add": 
            include("pages/solicitantes/solicitante_add.php");
            break;
        CASE "solicitante_update": 
            include("pages/solicitantes/solicitante_update.php");
            break;
        CASE "solicitante_drop": 
            include("pages/solicitantes/solicitante_drop.php");
            break;

// SECCION DEL MANTENIMIENTO DEL SISTEMA
        CASE "solicitante_mantenimiento": 
            include("pages/solicitantes/solicitante_mantenimiento.php");
            break;
        CASE "solicitante_mantenimiento_phone": 
            include("pages/solicitantes/solicitante_mantenimiento_phone_fijo.php");
            break;
        CASE "solicitante_mantenimiento_movil": 
            include("pages/solicitantes/solicitante_mantenimiento_phone.php");
            break;

// GESTION DE REPORTES
        CASE "gestion_reporte_est_fecha": 
            include("pages/gestion_reportes/gestion_estadistico_fecha.php");
            break;
        CASE "gestion_reporte_est_year": 
            include("pages/gestion_reportes/gestion_estadistico_year.php");
            break;
        CASE "gestion_reporte_est_unidad_fecha": 
            include("pages/gestion_reportes/gestion_estadistico_unidad_fecha.php");
            break;
        CASE "gestion_reporte_est_unidad_year": 
            include("pages/gestion_reportes/gestion_estadistico_unidad_year.php");
            break;
        CASE "gestion_reporte_ticket_fecha": 
            include("pages/gestion_reportes/gestion_ticket_fecha.php");
            break;
        CASE "gestion_reporte_tipo_tramite": 
            include("pages/gestion_reportes/gestion_reporte_tipo_tramite.php");
            break;
        CASE "gestion_reporte_categoria": 
            include("pages/gestion_reportes/gestion_reporte_categoria.php");
            break;
        CASE "gestion_reporte_municipio": 
            include("pages/gestion_reportes/gestion_reporte_municipio.php");
            break;
        CASE "gestion_reporte_parroquia": 
            include("pages/gestion_reportes/gestion_reporte_parroquia.php");
            break;
        CASE "gestion_reporte_comunidad": 
            include("pages/gestion_reportes/gestion_reporte_comunidad.php");
            break;
        CASE "gestion_reporte_unidad": 
            include("pages/gestion_reportes/gestion_reporte_unidad.php");
            break;
        CASE "gestion_reporte_solicitante": 
            include("pages/gestion_reportes/gestion_reporte_solititante.php");
            break;

///////////// DEPENDENCIAS /////////////////        
        CASE "punto_cuenta": 
            include("pages/punto_cuenta/aut_gestion_punto_cuenta.php");
            break;
        CASE "punto_cuenta_add": 
            include("pages/punto_cuenta/punto_cuenta_add.php");
            break;
        CASE "punto_cuenta_update": 
            include("pages/punto_cuenta/punto_cuenta_update.php");
            break;
        CASE "punto_cuenta_drop": 
            include("pages/punto_cuenta/punto_cuenta_drop.php");
            break;
        CASE "enviar_punto": 
            include("pages/punto_cuenta/punto_cuenta_enviar.php");
            break;
        CASE "responder_punto_cuenta": 
            include("pages/punto_cuenta/punto_cuenta_decision.php");
            break;
        CASE "notificar_punto_cuenta": 
            include("pages/punto_cuenta/punto_cuenta_notificar.php");
            break;

// POR DEFECTO CUANDO VIEW NO POSEE VALOR SE LLAMA AL FORMULARIO DE AUNTENTICACION
        default: 
            include("pages/home.php");
//            include("panel.php");
	
        
    }
?>

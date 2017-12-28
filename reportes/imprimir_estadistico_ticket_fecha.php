<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    
    //RECIBIENDO VALORES
    if (isset($_POST['fecha_ini'])){
        $fecha_ini=$_POST["fecha_ini"]; 
        $fecha_fin=$_POST["fecha_fin"];
    }

    $pdf=new PDF('L','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
        
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    
    //$pdf->SetFillColor(200,220,255); //AZUL
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);
    
    $pdf->SetFont('Arial','B',14);
    $pdf->SetLeftMargin(55);
    
    $pdf->SetY(20);
    $pdf->Cell(0,6,utf8_decode('ESTADISTICO DE TICKET ASIGNADOS POR FECHA'),0,1,'C',0);//
    
    $pdf->SetY(32);
    $pdf->SetFont('Times','B',12);
    $pdf->MultiCell(0,6,'FECHA DESDE '.implode('/',array_reverse(explode('-',$fecha_ini))).' HASTA '.implode('/',array_reverse(explode('-',$fecha_fin))),0,'R',0);//
    
    $pdf->SetFontSize(9);
    $pdf->Line(10,38,269,38);
    $pdf->Ln(15);
    
    $pdf->SetLeftMargin(10);
    $pdf->SetX(10);
    $pdf->SetY(40);
    
    //Consulta
    $query = "SELECT * FROM unidades WHERE status_unidad=1 order by nombre_unidad ASC";
    $result_detalle = pg_query($query);
    $total_registros=  pg_num_rows($result_detalle);
    
    ///////////// FORMATO Y CABEZERA DE LA LISTA DE PERSONAL ///////////////////////////
    $pdf->SetFont('Times','B',11);
    $pdf->SetLeftMargin(10);

    $pdf->SetFillColor(200,220,255);	
    $pdf->SetFont('Times','B',8);	
    $pdf->SetWidths(array(12,65,46,45,45,46));	//336 total ancho PAGINA HORIZONTAL
    $pdf->SetAligns(array('C','C','C','C','C','C'));    
    
    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("COD"),utf8_decode("NOMBRE DE UNIDAD / DEPENDENCIA"),utf8_decode("TICKET REGISTRADOS EN AC."),utf8_decode("TICKET PENDIENTES"),utf8_decode("TICKET COMPLETADOS"),utf8_decode("TICKET CANCELADOS")));
    $fill = false;
    $pdf->fill("$fill");
    $fila=1;
    
    $num=0;
////////////////////////////////////////////////////////////////////////////////////
    while ($row = pg_fetch_array($result_detalle)){
        $num++;
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND detalles_ticket.fecha_registro>='$fecha_ini' AND detalles_ticket.fecha_registro<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_pendientes=pg_fetch_row($result);	
        pg_free_result($result);
        
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND detalles_ticket.fecha_registro>='$fecha_ini' AND detalles_ticket.fecha_registro<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_completados=pg_fetch_row($result);	
        pg_free_result($result);
        
        $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
                " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
                " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
                " AND detalles_ticket.fecha_registro>='$fecha_ini' AND detalles_ticket.fecha_registro<='$fecha_fin' AND detalles_ticket.cod_unidad='$row[cod_unidad]' ";
        $result = pg_query($query)or die(pg_last_error());
        $resultados_ticket_cancelados=pg_fetch_row($result);	
        pg_free_result($result);
                
        $query="SELECT COUNT(*) FROM ticket,tramites". 
                " WHERE  ticket.fecha_registro>='$fecha_ini' AND ticket.fecha_registro<='$fecha_fin' ".
                " AND ticket.cod_tramite=tramites.cod_tramite ".
                " AND tramites.cod_unidad='$row[cod_unidad]'";
        $result = pg_query($query)or die(pg_last_error());	
        $resultados_ticket=pg_fetch_row($result);	
        pg_free_result($result);
        
        $pdf->SetFillColor(230,235,255);
        $pdf->SetFont('Times','',7);	
        $pdf->SetWidths(array(12,65,46,45,45,46));	//196 total ancho	
        $pdf->SetAligns(array('C','L','C','C','C','C'));
        $pdf->fill("$fill");
        
        $pdf->Row(array(utf8_decode($num),  utf8_decode($row[cod_unidad].' - '.$row['nombre_unidad']),utf8_decode($resultados_ticket[0]),utf8_decode($resultados_ticket_pendientes[0]),utf8_decode($resultados_ticket_completados[0]),utf8_decode($resultados_ticket_cancelados[0])));
    }  
    $pdf->Ln(1);
    $pdf->SetFont('Times','B',9);
    
    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=1 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND detalles_ticket.fecha_registro>='$fecha_ini' AND detalles_ticket.fecha_registro<='$fecha_fin' ";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_pendientes=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=2 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND detalles_ticket.fecha_registro>='$fecha_ini' AND detalles_ticket.fecha_registro<='$fecha_fin'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_completados=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket,detalles_ticket,estados_tramites". 
            " WHERE ticket.cod_estado_tramite=estados_tramites.cod_estado_tramite ".
            " AND estados_tramites.tipo_estado_tramite=3 AND ticket.cod_subticket=detalles_ticket.cod_detalle_ticket ".
            " AND detalles_ticket.fecha_registro>='$fecha_ini' AND detalles_ticket.fecha_registro<='$fecha_fin'";
    $result = pg_query($query)or die(pg_last_error());
    $resultados_total_ticket_cancelados=pg_fetch_row($result);	
    pg_free_result($result);

    $query="SELECT COUNT(*) FROM ticket". 
            " WHERE  ticket.fecha_registro>='$fecha_ini' AND ticket.fecha_registro<='$fecha_fin'";
    $result = pg_query($query)or die(pg_last_error());	
    $resultados_total_ticket=pg_fetch_row($result);	
    pg_free_result($result);
    
    
    $pdf->Cell(77,5,utf8_decode('TOTAL GENERAL DE TAC:'),1,0,'L',0);//
    $pdf->Cell(46,5,$resultados_total_ticket[0],1,0,'C',0);//
    $pdf->Cell(45,5,$resultados_total_ticket_pendientes[0],1,0,'C',0);//
    $pdf->Cell(45,5,$resultados_total_ticket_completados[0],1,0,'C',0);//
    $pdf->Cell(0,5,$resultados_total_ticket_cancelados[0],1,1,'C',0);//
    
    pg_close();
    $pdf->Output("estadistico_tac_fecha.pdf","I");
?>

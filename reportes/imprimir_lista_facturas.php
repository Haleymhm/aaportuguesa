<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    // RECIBIENDO LOS VALORES
    if (isset($_POST["fecha_cierre"])){
	$fecha_cierre=$_POST["fecha_cierre"];
    }
    if (isset($_GET["fecha_cierre"])){
	$fecha_cierre=$_GET["fecha_cierre"];
    }
    
    $query= "Select * from empresa";
    $result = pg_query($query);
    $resultados_empresa=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf=new PDF('P','mm','Letter'); // Tamaño del Papel Personalizado
//    $pdf=new PDF('P','mm','MLetter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();
    
    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    $pdf->Image('./logo/logo_busportuguesa.png',140,10,'70','','png','http://busportuguesa.com.ve/');
    $pdf->Image('./logo/logo_proveduria_busportuguesa.png',150,20,'50','','png','http://busportuguesa.com.ve/');

    $pdf->SetLeftMargin(45);
    $pdf->SetFillColor(200,200,200);//GRIS
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.1);

    $pdf->SetFontSize(12);
    $pdf->MultiCell(85,4,utf8_decode($resultados_empresa['nombre_empresa']),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(16);
    $pdf->MultiCell(85,6,utf8_decode($resultados_empresa['siglas_empresa']),0,'C',0);//
    $pdf->Ln(0);
    $pdf->SetFontSize(12);
    $pdf->MultiCell(85,6,'RIF: '.$resultados_empresa['rif_empresa'],0,'C',0);//

    $pdf->Ln(1);
    $pdf->SetFontSize(9);
    $pdf->Cell(16,6,utf8_decode('Dirección:'),'T',0,'L',0);//
    $pdf->SetFont('');
    $pdf->MultiCell(85,6,utf8_decode($resultados_empresa['direccion_empresa']),'T','J',0);
    $pdf->Line(10,41,205,41);
    $pdf->Ln(2);
    
    $pdf->SetFont('Arial','B',12);
    $pdf->SetLeftMargin(25);
    $pdf->SetY(35);
    
    $pdf->Cell(0,6,utf8_decode('CIERRE DIARIO DE FACTURACIÓN AL '.date_format(date_create($fecha_cierre), 'd/m/Y')),0,1,'C',0);
    $pdf->SetY(43);
    $pdf->SetX(10);
    
    //Cabecera
    $pdf->SetLeftMargin(10);
    $pdf->SetFillColor(85,186,243);	
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',9);

    $pdf->SetWidths(array(19,90,18,18,18,18,12));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('C','C','C','C','C','C','C'));

    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("Fáctura"),utf8_decode("Razón Social"),utf8_decode("Mto Fáct"),utf8_decode("Efectivo"),utf8_decode("Depósito"),utf8_decode("Cheque"),utf8_decode("Status")));

    //Restauracion de colores y fuentes
    $pdf->SetFillColor(246,246,246);
    $pdf->SetTextColor(0);

    $query="SELECT factura.n_factura, factura.cedula_rif,solicitantes.nombre_solicitante,solicitantes.nombre_solicitante, factura.fecha_factura, factura.iva ,SUM(detalle_factura.monto_concepto*detalle_factura.cantidad)  as total_factura,SUM((detalle_factura.monto_concepto*detalle_factura.cantidad*factura.iva)/100)  as total_factura_iva , factura.status,factura.monto_efectivo,factura.monto_deposito,factura.monto_cheque,COUNT(*) as nro_detalles
            FROM detalle_factura INNER JOIN factura ON detalle_factura.n_factura=factura.n_factura INNER JOIN solicitantes ON solicitantes.cedula_rif = factura.cedula_rif
            WHERE to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') = '$fecha_cierre' and factura.status::text like  '%$condicion%'
            GROUP BY factura.n_factura,solicitantes.cedula_rif ORDER BY factura.n_factura";
    $result_detalle = pg_query($query);

    $total_facturado=0;
    $total_efectivo=0;
    $total_deposito=0;
    $total_cheque=0;
    
    while ($row=pg_fetch_array($result_detalle)){
        //DATOS PRINCIPALES	
        $importe= $row['total_factura'];
        $importe_iva=$row['total_factura_iva'];
        $total_factura=number_format($importe+$importe_iva,2,".","");
//        $total_factura=sprintf("%01.2f", $importe+$importe_iva);
        $total_facturado+=$total_factura;
        
        $factura=str_pad($row['n_factura'],9,"0",STR_PAD_LEFT);
        $nombre=substr_replace($row['cedula_rif'],'-',1,0).'  -  '.utf8_decode($row['nombre_solicitante']);
        $monto=$total_factura;
        $efectivo=number_format($row['monto_efectivo'],2,".","");
        $deposito=number_format($row['monto_deposito'],2,".","");
        $cheque=number_format($row['monto_cheque'],2,".","");
        
        if ($row['status']==1) {
            $total_efectivo+=$efectivo;
            $total_deposito+=$deposito;
            $total_cheque+=$cheque;
        }
//        if ($row['status']==0){
//            $status='ANULADO';
//        }else {
//            $status='ACTIVO';
//        }
        $status=$row['status'];
        
        $pdf->SetFillColor(230,235,255);
        $pdf->SetWidths(array(19,90,18,18,18,18,12));	//196 para P; 259.4 para L
        $pdf->SetAligns(array('L','L','R','R','R','R','C'));

        $fill = false;
        $pdf->fill("$fill");
        $pdf->Row(array($factura,$nombre,$monto,$efectivo,$deposito,$cheque,$status));
    };
    $pdf->SetFillColor(230,235,255);
    $pdf->SetWidths(array(109,18,18,18,18,12));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('R','R','R','R','R'));
    $pdf->Row(array("TOTALES",number_format($total_facturado,2,".",""),number_format($total_efectivo,2,".",""),number_format($total_deposito,2,".",""),number_format($total_cheque,2,".","")));

    $pdf->Ln(25);
    $pdf->SetX(0);
    $pdf->SetLeftMargin(10);
    $pdf->Cell(65,5,utf8_decode($resultados_empresa['nombre_administrador']),'T',0,'C');
    $pdf->Cell(65,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode($cajero),'T',0,'C');
    $pdf->Ln(5);
    $pdf->Cell(65,5,utf8_decode('Administrador'),0,0,'C');
    $pdf->Cell(65,5,"",0,0,'C');
    $pdf->Cell(65,5,utf8_decode('Cajero'),0,0,'C');
    
    pg_close();
    $pdf->Output("Factura-".$fecha_cierre.".pdf","I");
?>

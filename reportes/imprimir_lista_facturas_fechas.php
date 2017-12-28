<?php
    include("fpdf17/conectar.php");
    include("fpdf17/funciones.php");
    require ("../funciones.php"); // llamado de funciones de la pagina
    
    //RECIBIENDO VALORES
    if (isset($_POST['fecha_ini'])){
        $fecha_i=$_POST['fecha_ini']; 
        $fecha_f=$_POST['fecha_fin'];
        $fecha_1=implode('/',array_reverse(explode('-',$_POST['fecha_ini'])));
        $fecha_2=implode('/',array_reverse(explode('-',$_POST['fecha_fin'])));
    }
    
    $query= "Select * from empresa";
    $result = pg_query($query);
    $resultados_empresa=pg_fetch_array($result);
    pg_free_result($result);
    
    $pdf=new PDF('L','mm','Letter');
    $pdf->AliasNbPages();
    $pdf->Open();
    $pdf->AddPage();

    $pdf->SetFont('Arial','B',16);
    $pdf->Image('./logo/logo.jpg',10,10,'32','','jpg','http://www.portuguesa.gob.ve');
    $pdf->Image('./logo/logo_busportuguesa.png',200,10,'70','','png','http://busportuguesa.com.ve/');
    $pdf->Image('./logo/logo_proveduria_busportuguesa.png',210,20,'50','','png','http://busportuguesa.com.ve/');

    $pdf->SetLeftMargin(80);
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
    $pdf->Line(10,41,270,41);
    
    $pdf->SetFont('Arial','B',12);
    $pdf->SetLeftMargin(8);

    $pdf->SetFillColor(255,255,255);
    $pdf->SetFont('Arial','B',13);
    $pdf->SetY(35);
    $pdf->SetX(0);

    //
    $pdf->Ln(1);
    $pdf->MultiCell(0,6,utf8_decode('FACTURACIÓN DESDE EL '.$fecha_1.' AL '.$fecha_2),0,C,0);//
    $pdf->SetFont('Arial','B',10);
    $pdf->SetX(10);
    $pdf->Ln(3);    

    //Colores, ancho de linea y fuente en negrita
    $pdf->SetFillColor(85,186,243);
    $pdf->SetTextColor(255);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.3);
    $pdf->SetFont('Arial','B',10);

    //Cabecera
    $pdf->SetLeftMargin(15);
    $pdf->SetFillColor(85,186,243);	
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','B',9);	

    $pdf->SetWidths(array(22,100,22,22,22,22,22,13));	//196 para P; 259.4 para L
    $pdf->SetAligns(array('C','C','C','C','C','C','C'));

    $fill = true;
    $pdf->fill("$fill");
    $pdf->Row(array(utf8_decode("Fáctura"),utf8_decode("Razón Social"),utf8_decode("Fecha"),utf8_decode("Mto Fáct"),utf8_decode("Efectivo"),utf8_decode("Depósito"),utf8_decode("Cheque"),utf8_decode("Status")));

    //Restauracion de colores y fuentes
    $pdf->SetFillColor(246,246,246);
    $pdf->SetTextColor(0);

    $query="SELECT factura.n_factura, factura.cedula_rif,solicitantes.nombre_solicitante, factura.fecha_factura, factura.iva ,SUM(detalle_factura.monto_concepto*detalle_factura.cantidad)  as total_factura,SUM((detalle_factura.monto_concepto*detalle_factura.cantidad*factura.iva)/100)  as total_factura_iva , factura.status,factura.monto_efectivo,factura.monto_deposito,factura.monto_cheque,COUNT(*) as nro_detalles
            FROM detalle_factura INNER JOIN factura ON detalle_factura.n_factura=factura.n_factura INNER JOIN solicitantes ON solicitantes.cedula_rif = factura.cedula_rif
            WHERE to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') >= '$fecha_i' and to_date(to_char(factura.fecha_factura,'YYYY-MM-DD'),'YYYY-MM-DD') <= '$fecha_f' and factura.status::text like  '%$condicion%'
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
        $fecha=date_format(date_create($row['fecha_factura']), 'd/m/Y');
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
        $pdf->SetWidths(array(22,100,22,22,22,22,22,13));	//196 para P; 259.4 para L
        $pdf->SetAligns(array('L','L','C','R','R','R','R','C'));

        $fill = false;
        $pdf->fill("$fill");
        $pdf->Row(array($factura,$nombre,$fecha,$monto,$efectivo,$deposito,$cheque,$status));
    };
    $pdf->SetFillColor(230,235,255);
    $pdf->SetWidths(array(144,22,22,22,22,13));	//196 para P; 259.4 para L
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

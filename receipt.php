<?php
    include 'conn.php';
    session_start();
    require('fpdf/fpdf.php');
    if(!isset($_SESSION['lastid'])){
        $_SESSION['lastid'] = 0;
    }
    
    $lastID = $_SESSION['lastid'];

    $sql = "SELECT * FROM invoice AS I INNER JOIN payment AS P ON I.paymentID = P.paymentID WHERE I.paymentID = $lastID";

    $query = $conn->query($sql);

    while($row = $query->fetch_assoc()){
        $ID = $row['invoiceID'];
        $date = $row['invoiceDate'];
        $totalNoTax = $row['invoiceTotalnotax'];
        $tax = $row['tax'];
        $totalWithTax = $row['invoiceTotalwithtax'];
        $note = $row['note'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $add = $row['address'];
        $dist = $row['district'];
        $cname = $row['nameOnCard'];
        $cardnum = $row['cardNum'];
        $exp = $row['expiration'];
        $cvv = $row['cvv'];
        $pname = $row['accountName'];
        $pemail = $row['paypalEmail'];
    }

    $date = "Invoice Date: ".$date;
    $totalNoTax = "$".$totalNoTax;
    $tax = $tax."%";
    $totalWithTax = "$".$totalWithTax;
    
    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
        global $ID;
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(1);
        // Title
        $this->Cell(80,10,'Best Online Shopping App',1,0,'C');
        //Arial
        $this->SetFont('Arial','',11);
        // Move to the right
        $this->Cell(65);
        // Title
        $this->Cell(30,10,'Invoice No.',1,0,'C');
        //Arial
        $this->SetFont('Arial','I',11);
        // Move to the right
        $this->Cell(4);
        // Title
        $this->Cell(10,10, $ID,1,0,'C');
        // Line break
        $this->Ln(20);
    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    }
    
    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);
    
    //date and name
    $pdf->Cell(0,10,$date,1,1);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,"Name",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(75,10,$fname,1,0);
    $pdf->Cell(75,10,$lname,1,0);

    //address
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,"Address",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(75,10,$add,1,0);
    $pdf->Cell(75,10,$dist,1,0);

    //card payment
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Card Payment Method",1,1);
    $pdf->Cell(40,10,"Name On Card",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(150,10,$cname,1,0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,"Card Number",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(150,10,$cardnum,1,0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,"Expiration Date",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(150,10,$exp,1,0);
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,10,"CVV",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(150,10,$cvv,1,0);

    //paypal payment
    $pdf->Ln();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Paypal Payment Method",1,1);
    $pdf->Cell(40,10,"Name and Email",1,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(75,10,$pname,1,0);
    $pdf->Cell(75,10,$pemail,1,0);

    //total and tax
    $pdf->Ln();
    $pdf->Cell(0,10,"",0,1); 
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,"Total Costs",1,1);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(150,10,"Items Total",1,0);
    $pdf->Cell(40,10,$totalNoTax,1,0);
    $pdf->Ln();
    $pdf->Cell(150,10,"Tax",1,0);
    $pdf->Cell(40,10,$tax,1,0);
    $pdf->Ln();
    $pdf->Cell(150,10,"Final Total",1,0);
    $pdf->Cell(40,10,$totalWithTax,1,0);

    //note
    $pdf->Ln();
    $pdf->Cell(0,10,"",0,1); 
    $pdf->Cell(0,10,"",0,1); 
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,$note,0,1,'C'); 


    $pdf->Output();
?>

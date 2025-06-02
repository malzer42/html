<?php
require 'db_arche.php';
require 'vendor/autoload.php'; // Load FPDF and PHPMailer
use FPDF;

use PHPMailer\PHPMailer\PHPMailer;

$member_id = $_POST['member_id'];

// Get member info and donation sum
$stmt = $pdo->prepare("SELECT first_name, last_name, email FROM members WHERE id = ?");
$stmt->execute([$member_id]);
$member = $stmt->fetch();

$stmt = $pdo->prepare("SELECT SUM(amount) as total FROM donations WHERE member_id = ?");
$stmt->execute([$member_id]);
$donation = $stmt->fetch();

// Create PDF
require('fpdf186/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(80,20,"Eglise l'Arche de Dieu");
$pdf->SetFont('Arial','I',14);
$pdf->Cell(40,20,"Recu d'impots pour {$member['first_name']} {$member['last_name']}");
$pdf->Ln(20);
$pdf->SetFont('Arial','',14);
$pdf->Cell(40,10,"Total Donations: $" . number_format((float)($donation['total'] ?? 0), 2));
$pdf->Image('signature.png', 50, 200, 40); // (x, y, width)
$pdf->Image('signature.png', 110, 200, 40); // (x, y, width)

// Optional: Label for signature
$pdf->SetXY(50, 185);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 10, 'Jean Claude Mutombo', 0, 0, 'C');
$pdf->Cell(80, 10, 'Helene Ciabu', 0, 0, 'C');
$pdf_path = "tax_returns/tax_{$member_id}.pdf";
$pdf->Output('F', $pdf_path);

// Send email
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Update if different
$mail->SMTPAuth = true;
$mail->Username = 'pmulamba@gmail.com'; // Replace
$mail->Password = 'lbtqienrhrjpqvde'; // Replace with App Password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('pmulamba@gmail.com', ' Administration Arche de Dieu');
$mail->addAddress($member['email']);
$mail->Subject = 'Recu Retour Impots';
$mail->Body = 'En piece jointe vous trouverez votre recu  Impots.';
$mail->addAttachment($pdf_path);

if ($mail->send()) {
    //echo "Tax return sent to " . htmlspecialchars($member['email']);
    header("Location: arche_tax.php?success=1");
    exit;
} else {
    echo "Email failed: " . $mail->ErrorInfo;
}
?>

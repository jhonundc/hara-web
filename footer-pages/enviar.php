<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $mensaje = $_POST['mensaje'];

    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '2201010281@undc.edu.pe'; // Tu correo Gmail
        $mail->Password   = 'hhmhuftmfibvpumk'; // Contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom($correo, $nombre);
        $mail->addAddress('2201010281@undc.edu.pe'); // A dónde te llegará el mensaje

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Mensaje desde formulario de contacto';
        $mail->Body    = "
            <h3>Nuevo mensaje de contacto</h3>
            <p><strong>Nombre:</strong> $nombre</p>
            <p><strong>Correo:</strong> $correo</p>
            <p><strong>Teléfono:</strong> $telefono</p>
            <p><strong>Mensaje:</strong><br>$mensaje</p>
        ";

        if ($mail->send()) {
            header('Location: ../index.php?exito=1');
            exit();
        } else {
            header('Location: ../index.php?error=1');
            exit();
        }

    } catch (Exception $e) {
        // Si ocurre un error al enviar el mensaje
        header('Location: ../index.php?error=1');
        exit();
    }
}
?>

<?php
require_once "utils/PHPMailer/PHPMailer.php";
require_once "utils/PHPMailer/Exception.php";
require_once "utils/PHPMailer/SMTP.php";
use PHPMailer\PHPMailer\PHPMailer;
Class UtilMail
{
    public static function EnvoyerMail($destinataire,$sujet,$message)
    {
        // return true; // Décommentez pour supprimer l'envoi de mails
        $enLigne=false; // true en production chez OVH, false pour test sur localhost
        $mail=new PHPMailer();
        $mail->isSMTP();
        $mail->isHTML(false);
        $mail->CharSet="utf-8";
        $mail->SMTPAutoTLS = false;
        $mail->SMTPAuth = true;
        if ($enLigne)
        {
            $mail->Host = 'ssl0.ovh.net';
            $mail->Username = 'zreservation@musiquenherbe.org';
            $mail->Password = 'MusiquE80';
            $mail->setFrom('zreservation@musiquenherbe.org');
        }
        else
        {
            $mail->Host = 'smtp.orange.fr';
            $mail->Username = 'francemobilier@orange.fr';
            $mail->Password = 'Megabit2';
            $mail->setFrom('francemobilier@orange.fr');
        }
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Subject = $sujet;
        $mail->addAddress($destinataire);
        $mail->Body = $message;
        return $mail->Send();    
    }
}
?>
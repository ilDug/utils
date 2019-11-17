<?php

namespace DAG;
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * classe per l'invio di mail con l'account del dominio
 */
class Mail
{
    const HOST = "srv-hp1.netsons.net";
    const PORT = 25;
    const USER = "hello@looko.it";
    const PASSWORD = "ZDA1HQOvnzZ1";
    const SENDER_ADDRESS = "hello@looko.it";
    const SENDER_NAME = "Hello Looko";

    private $receiver = array();
    private $subject;
    private $body;

    function __construct()
    { }

    //1
    public function setSubject(string $subject)
    {
        $this->subject = $subject ? $subject : "Auth Server Message";
        return $this;
    }

    //2
    public function setBody(string $body)
    {
        $this->body = $body ? $body : "empty email - some error occurs";
        return $this;
    }

    //3 (multiple)
    public function addReceiver(string $receiver)
    {
        $this->receiver[] = $receiver;
        return $this;
    }



    /**
     * invia la mail e ritorna il risultato della risposta
     */
    public function send()
    {
        //Create a new PHPMailer instance
        //Passing true to the constructor enables the use of exceptions for error handling
        $mail = new PHPMailer(true);


        try {
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            //$mail->Debugoutput = 'html';

            //Set the hostname of the mail server
            $mail->Host = self::HOST;
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = self::PORT;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = self::USER;
            //Password to use for SMTP authentication
            $mail->Password = self::PASSWORD;
            //Set who the message is to be sent from
            $mail->setFrom(self::SENDER_ADDRESS, self::SENDER_NAME);

            //Set an alternative reply-to address
            //$mail->addReplyTo('replyto@example.com', 'First Last');

            foreach ($this->receiver as $email) {
                //Set who the message is to be sent to
                $mail->addAddress($email);
            }

            //Set the subject line
            $mail->Subject = $this->subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $mail->msgHTML($this->body);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'error - Please, try read this mail in a HTML reader';

            $delivery = $mail->send();

            $mail->SmtpClose();
            unset($mail);

            return $delivery;
        } catch (Exception $e) {
            throw new \Exception($e->errorMessage(), 500);
            return false;
        }
    }
}//chide classe
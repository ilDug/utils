<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * classe per l'invio dimail con l'account FLATMAC
 */
class DagMail
{
    private $sender;
    private $pw;
    private $user;
    private $host;
    private $port;
    private $recipients = array();
    private $subject;
    private $body;


    function __construct($sender)
    {
        $this->host = "srv-hp1.netsons.net ";
		$this->port = 25;


        switch ($sender) {
            case 'hello':
                $this->pw = 'ZDA1HQOvnzZ1';
                $this->user = "hello@looko.it";
                $this->sender->address = "hello@looko.it";
                $this->sender->name="Hello Looko";
                break;

            case 'roby':
                $this->pw = 'bFaPSkn6MyHG';
                $this->user = "roby@looko.it";
                $this->sender->address = "roby@looko.it";
                $this->sender->name="Looko Tips";
                break;
        }

    }

//1
    public function setSubject(string $subject){
        $this->subject = $subject ? $subject : "Auth Server Message";
        return $this;
    }

//2
    public function setBody(string $body){
        $this->body = $body ? $body : "empty email - some error occurs";
        return $this;
    }

//3 (multiple)
    public function addRecipient(string $recipient){
        $this->recipients[] = $recipient;
        return $this;
    }



    /**
     * invia la mail e ritorna il risultato della risposta
     */
    public function send(){
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
            $mail->Host = $this->host;
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = $this->port;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $this->user;
            //Password to use for SMTP authentication
            $mail->Password = $this->pw;
            //Set who the message is to be sent from
            $mail->setFrom($this->sender->address, $this->sender->name);

            //Set an alternative reply-to address
            //$mail->addReplyTo('replyto@example.com', 'First Last');

            foreach ($this->recipients as $email ) {
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
            throw new Exception($e->errorMessage(), 500);
            return false;
        }
            
    }


}//chide classe
 ?>

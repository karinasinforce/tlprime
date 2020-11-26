<?php
header('Access-Control-Allow-Origin: *');

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require '../../vendor/autoload.php';


$remetente = $_POST['remetente'];
$nome_remetente = $_POST['nome_remetente'];

$nome_do_destinatario = $_POST['nome_do_destinatario'];
$destinatario = $_POST['destinatario'];

$mensagem = $_POST['Mensagem'];

$receber_copia = true;
$imovel = $_POST['codigo_imovel'];
$url_imovel = $_POST['url_imovel'];

$mail = new PHPMailer(true);  // Passing `true` enables exceptions


try {
    //Server settings
    // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtplw.com.br';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'inforce2';                 // SMTP username
    $mail->Password = 'uRVvhfeR8065';                           // SMTP password
    $mail->SMTPSecure = false;                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    $mail->CharSet = "UTF-8";

    //Recipients
    $mail->setFrom('naoresponda@inforcemail.com.br', 'Sampaio');
    $mail->addAddress($destinatario);     // Add a recipient
    // $mail->addAddress($remetente);               // Name is optional
    $mail->addReplyTo($remetente);
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    $nome_assunto = $nome_remetente == '' ? 'Sampaio Imóveis' : $nome_remetente;
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject =  $nome_assunto . ' enviou um imóvel para você!';

    $mail->Body    = 'Olá '. $nome_do_destinatario .', seu amigo(a) <strong>'. $nome_remetente .'</strong> enviou o imóvel <a href="'. $url_imovel .'">'. $imovel . '</a> da <strong>Sampaio Imóvel</strong>.<br>';
    $mail->Body    .= '<a href="'. $url_imovel .'">Clique aqui</a> e confira este imóvel ou visite nosso site <a href="http://www.sampaioimoveis.com.br">www.sampaioimoveis.com.br</a> <br><br>';
    
    $mail->Body    .= '<strong>Dados do(a)</strong> ' . $nome_remetente;
    $mail->Body    .= '<br><hr><br>';
    $mail->Body    .= '<strong>Nome:</strong> '. $nome_remetente;
    $mail->Body    .= '<br><strong>Email:</strong> '. $remetente;
    $mail->Body    .= '<br><strong>Mensagem</strong> <br/>';
    $mail->Body    .=  $mensagem;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


    $mail->send();

    $resposta = array(
        'resposta' => 'Mensagem enviada consucesso!'
    );

    
    echo json_encode($resposta);


} catch (Exception $e) {
    // echo 'Mensagem não enviada. <br>Mailer Error: ', ;
    
    $resposta = array(
        'erro' => $mail->ErrorInfo, 
        'resposta' => 'Mensagem não enviada, tente novamente mais tarde.'
    );


    echo json_encode($resposta);

}
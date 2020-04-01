<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
//     $to         = "tuyenbui3030@gmail.com";
//     $subject    = wordwrap($_POST['subject'], 70);
//     $body       = $_POST['body'];
//    echo $header     = $_POST['email'];
//     mail($to,"$subject",$body);
    $mail = new PHPMailer(true);
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'devautosend@gmail.com';                     // SMTP username
    $mail->Password   = 'meomeogaugau';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;      

    //Recipients
    $mail->setFrom($_POST['email'], $_POST['name']);
    $mail->addAddress('Tuyenbui3030@gmail.com');                   // Name is optional
    $mail->addReplyTo($_POST['email'],$_POST['name']);
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'From Submission: ' . $_POST['subject'];
    $mail->Body    = $_POST['body'];
    $mail->send();
} 

?>

<!-- Navigation --> 

<?php include "includes/navigation.php"; ?>


<!-- Page Content -->
<div class="container">

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-wrap">
                        <h1>Contact</h1>
                        <form role="form" action="contact.php" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                                <label for="name" class="sr-only">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your Name">
                            </div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email">
                            </div>
                            <div class="form-group">
                                <label for="subject" class="sr-only">Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter your Subject">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="body" id="body" cols="50" rows="10"></textarea>
                            </div>

                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit">
                        </form>

                    </div>
                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>


    <hr>



    <?php include "includes/footer.php"; ?>
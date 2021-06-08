<?php

page_template(function() {

$error = null;
$success_response = null;
$message_sent = "Thank you! Your message has been sent. We'll get in touch with you shortly.";

if (get_locale() == 'es_MX') {
   $message_sent = '¡Gracias! Su mensaje ha sido mandado. Nos pondremos en contacto pronto.';
}

$submitted = get_query_var('submitted');
if (!empty($submitted)) {
   $success_response = $message_sent;
}

if ($_POST['contact-submitted']) {
   $school_email = get_option('email');
   $recip = $school_email;
   
   $missing_content = 'Recipient, name, and message are required. Please try again.';
   $email_invalid   = 'The email address you provided is invalid. Please try again.';
   $message_unsent  = 'Your message was not sent. Please try again.';
   
   $recipient = $_POST['message_recipient'];
   $name = $_POST['message_name'];
   $email = $_POST['message_email'];
   $phone = $_POST['message_phone'];
   $address = $_POST['message_address'];
   $city = $_POST['message_city'];
   $state = $_POST['message_state'];
   $zip = $_POST['message_zip'];
   $message = $_POST['message_text'];
   $preference = $_POST['message_preference'];
   
   if (!empty($recipient)) {
      $recipient_address = get_option("contact-recipient-$recipient-address");
   }
   
   $subject = $name . ' sent a message from The Open School\'s website';

   $headers[] = 'MIME-Version: 1.0';
   $headers[] = 'Content-type: text/html; charset=iso-8859-1';
   $headers[] = "From: $name <$school_email>";
   $headers[] = "Reply-to: $email";
   
   $message = '<html><head></head><body><p>' . $message;
   
   if ($phone) {
      $message = $message . ' <p>Phone number: ' . $phone;
   }

   if ($preference) {
      $message = $message . ' <p>Preferred method of contact: ' . $preference;
   }
   
   if ($address) {
      $message = $message . ' <p>Address:<br>' . $address;
      if ($city) {
         $message = $message . '<br>' . $city;   
      }
      if ($state) {
         $message = $message . ', ' . $state;   
      }
      if ($zip) {
         $message = $message . ' ' . $zip;   
      }
   }
   
   $message = $message . '</body></html>';

   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = $email_invalid;
   }
   else if (empty($recipient) || empty($name) || empty($message)) {
      $error = $missing_content;
   }
   else {
      log_contact($name, $email, $message);
      if (mail($recipient_address, $subject, $message, implode("\r\n", $headers))) {
         $success_response = $message_sent;
      } else {
         $error = $message_unsent;
      }
   }
}
?>

   <div class="page">
      <?php
      
      if ($error) { ?>
         <div class='contact-us-error'><?php echo $error; ?></div>
      <?php }
      
      else if ($success_response) { ?>
         <div class='contact-us-success'><?php echo $success_response; ?></div>
      <?php }
      
      the_content();
      
      ?>
   </div>
   
<?php });
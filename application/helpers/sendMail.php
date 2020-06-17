<?php
$to = 'marksretech@gmail.com,pranshu.g@marks-retech.in,virat.s@marks-retech.in';
$subject = 'Cron testing';
$message = 'Congratulation ! Your Cron Job Run successfully'; 
$from = 'ashok.p@marks-retech.in';
// Sending email
mail($to, $subject, $message)
?>

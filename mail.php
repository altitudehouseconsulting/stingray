<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method Not Allowed'); }
if (!empty($_POST['_gotcha'])) { http_response_code(400); exit('Bad Request'); }
function clean_header($s) { return str_replace(array("\r","\n"), '', $s); }
$name = trim($_POST['name'] ?? ''); $email = trim($_POST['_replyto'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $message = trim($_POST['message'] ?? '');
if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(400); exit('Missing or invalid fields'); }
if (strlen($name)>200 || strlen($email)>200 || strlen($phone)>50 || strlen($message)>5000) { http_response_code(413); exit('Payload too large'); }
$replyTo = clean_header($email); $subject = 'New Website Inquiry — Stingray Home Solutions'; $to = 'Pkessinger@stingraysrq.com'; $from = 'noreply@stingraysrq.com';
$body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";
$headers = ["From: " . clean_header($from), "Reply-To: " . $replyTo, "MIME-Version: 1.0", "Content-Type: text/plain; charset=UTF-8"];
$ok = @mail($to, $subject, $body, implode("\r\n", $headers));
if ($ok) { header('Location: /thank-you.html'); exit; } http_response_code(500); echo 'Error sending email';

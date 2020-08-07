<?php
$to = "515094854w@gmail.com";
$subject = "Money";
$cost = 20;
$txt = "<html><body><H2> We got your MONEY!<H2><P><H3>Thank you for your $".$cost.", you rich dumm dumm.</H3></P></body></html>";
$headers = "From: TheNewIndeed@company.com" . "\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
mail($to, $subject, $txt, $headers);
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <title> Item Invoice {{ $invoice->invoice_number }}</title>
</head>
<body>
<p>Dear {{ $invoice->client->FIRSTNAME }} {{ $invoice->client->LASTNAME }},</p>
<p>Your InPlaceAuction item invoice is attached as a pdf document.</p>
<p>If you have any questions or need further clarification, please be sure to contact us at InPlaceAuction. </p>
<p> Thank you for business!</p>
<p>Sincerely,</p>
<p><b>Edward Castagna</b></p>
<p><b>Senior Appraiser, InPlaceAuction LLC.</b></p>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> IPA Client Invoice </title>
    <style type="text/css">
         body{
           font-size: 14px;
           line-height: 18px;
         }
         h2, h5{
              text-align: center;
              margin:5px 0;
         }
         h4{
              margin:5px 0;
         }
         table {
              width:100%;
         }
         table#top td{
              border: none;
         }
         table#top{
              border: none;
         }
         table, th, td {
              border: 1px solid grey;
              border-collapse: collapse;
         }
         table#invoiceInfo{
             border: none;
         }
         table#invoiceInfo td{
             border: none;
         }
         th, td {
              padding: 5px 10px;
              text-align: left;
              font-size: 12px;
         }
         table tr.specialHeading{
              background-color: #fff !important;
         }
         table#items tr:nth-child(even) {
              background-color: #eee;
         }
         table#items tr:nth-child(odd) {
              background-color: #fff;
         }
         table#items th {
              background-color: #0074bd;
              color: white;
         }
    </style>
</head>
<body>
<table id="top" style="border-bottom: 3px solid #000; margin-bottom: 10px;">
     <tr>
          <td colspan="4">
               <img src="{{ asset('app-assets/images/logo/pdflogo.jpg') }}" alt="logo">
          </td>
          <td>
               68 South Service Road, Suite 100<br>
               Melville, NY 11747 <br>
               Tel: 516-229-1968   Fax: 516-882-7924
          </td>
     </tr>
</table>
<h2>InPlace Auction Invoice #{{ $clientInvoice->invoice_number }}</h2>
<h5>Date {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->create_dt)->format('j F, Y')  }}</h5>
<table id="invoiceInfo">
    <tr>
        <td style="text-align: left; padding-left: 0">
            <b>Bill to : </b> <br>
            <b>{{ $clientInvoice->client->COMPANY }}</b> <br>
            <b>{{ $clientInvoice->client->ADDRESS1 }}</b> <br>
            <b>{{ $clientInvoice->client->CITY }}, {{ $clientInvoice->client->STATE }},{{ $clientInvoice->client->ZIP }}</b><br>
            <b>{{ $clientInvoice->client->FIRSTNAME }} {{ $clientInvoice->client->LASTNAME }}</b><br>
            <b>{{ $clientInvoice->client->PHONE }}</b><br>
            <b>{{ $clientInvoice->client->EMAIL }}</b><br>
            <b>{{ $clientInvoice->client->FAX }}</b>
        </td>
    </tr>
</table>
      <h4 style="text-align: left"> Invoice Details :</h4>
        <table id="items">
            <tr>
                <th>Lease#</th>
                <th>Lease Co</th>
                <th>IPA #</th>
                <th>Expense Type</th>
                <th>Description</th>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            @if(isset($clientInvoice->lines) &&! empty($clientInvoice->lines))
                @foreach( $clientInvoice->lines as $item)
                    <tr>
                        <td>
                           {{ $assignment->lease_nmbr }}
                        </td>
                        <td>
                            {{ $assignment->ls_company }}
                        </td>
                        <td>
                            {{ $item->expense->item->ASSIGNMENT_ID }} - {{ $item->expense->item->ITEM_NMBR }}
                        </td>
                        <td>
                            {{ $item->expense_type }}
                        </td>
                        <td>
                            {{ $item->description }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->create_dt)->format('j F, Y')  }}
                        </td>
                        <td>
                            ${{ round($item->expense_amount,2) }}
                        </td>
                    </tr>
                @endforeach
                    @php $totalPrice = 0; @endphp
                @foreach( $clientInvoice->lines as $item)
                    @php $totalPrice += $item->expense_amount;@endphp
                @endforeach
                <tr>
                    <td colspan="7" style="text-align: right;"><b>Total Due : </b> ${{ round($totalPrice,2) }}</td>
                </tr>
            @endif
        </table>
        <h4 style="text-align: center; margin-top: 10px"> Payment Due Upon Receipt :</h4>
        <p style="text-align: center">Make all checks payable to:</p>
        <p style="text-align: center; font-weight: bold;">InPlace Auction LLC. <br>
        68 South Service Road, Suite 100 <br>
        Melville, NY 11747
        </p>
        <!--<p style="text-align: center">Fed ID #45-2591749</p>-->
        <h4 style="text-align: center; margin-top: 10px"> Wiring Instructions</h4>
        <p style="text-align: center">CHASE Bank 425 Glen Cove Rd, Roslyn Heights, NY 11577
            <br>
            Account # 925281479 - Routing ABA # 021000021
        </p>
        <p style="text-align: center; font-weight: bold">Thank you for your business!</p>
</body>
</html>

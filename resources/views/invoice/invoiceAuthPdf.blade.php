<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> IPA Customer Invoice </title>
    <style type="text/css">
         body{
           font-size: 13px;
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
          <td colspan="3">
               <img src="{{ asset('app-assets/images/logo/pdflogo.jpg') }}" style="width:200px;height: 80px;" alt="logo">
          </td>
          <td colspan="4">
               68 South Service Road, <br>Suite 100 Melville, NY 11747 <br>Tel: 516-229-1968   Fax: 516-882-7924
          </td>
     </tr>
</table>
<h2>InPlace Auction Invoice #{{ $authorization->invoice_number }}</h2>
<h5>Date {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $authorization->create_dt)->format('j F, Y')  }}</h5>
<table id="invoiceInfo">
    <tr>
        <td style="text-align: left; padding-left: 0">
            <b>Bill to : </b> <br>
            <b>{{ $authorization->customer->COMPANY }}</b> <br>
            <b>{{ $authorization->customer->ADDRESS1 }}</b> <br>
            <b>{{ $authorization->customer->CITY }}, {{ $authorization->customer->STATE }},{{ $authorization->customer->ZIP }}</b><br>
            <b>{{ $authorization->customer->FIRSTNAME }} {{ $authorization->customer->LASTNAME }}</b><br>
            <b>{{ $authorization->customer->PHONE }}</b><br>
            <b>{{ $authorization->customer->EMAIL }}</b>
        </td>
    </tr>
</table>
      <h4 style="text-align: left; margin-top: 10px;"> Invoice Details :</h4>
        <table id="items">
            <tr>
                <th>Item#</th>
                <th>QTY</th>
                <th>Make</th>
                <th>Model</th>
                <th>Ser</th>
                <th>Year</th>
                <th>Price</th>
            </tr>
            @if(isset($authorization->items) &&! empty($authorization->items))
                @foreach( $authorization->items as $item)
                    <tr>
                        <td>
                           {{ $item->item->ASSIGNMENT_ID }} - {{ $item->item->ITEM_NMBR }}
                        </td>
                        <td>
                            {{ $item->item->QUANTITY }}
                        </td>
                        <td>
                            {{ $item->item->ITEM_MAKE }}
                        </td>
                        <td>
                            {{ $item->item->ITEM_MODEL }}
                        </td>
                        <td>
                            {{ $item->item->ITEM_SERIAL }}
                        </td>
                        <td>
                            {{ $item->item->ITEM_YEAR }}
                        </td>
                        <td>
                            @if(isset($item->item->bids)&& !empty($item->item->bids))
                                @foreach( $item->item->bids as $bid)
                                    @if($bid->BID_ACCEPTED === 1)
                                        ${{ round($bid->BID,2) }}
                                    @endif
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7" style="text-align: right;"><b>Total Due : </b> ${{ round($authorization->invoice_amount,2) }}</td>
                </tr>
            @endif
        </table>
      <p>AS IS, WHERE IS except for title when applicable, Seller shall make no warranties regarding the Equipment. Seller believes the Equipment is accurately described, but does not guarantee this. Buyer is responsible for all storage, maintenance and insurance costs once full payment is received</p>
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

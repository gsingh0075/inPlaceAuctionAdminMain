<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> IPA Client Remittance </title>
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
<h2>InPlace Auction Remittance #{{ $clientRemittance ->CLIENT_REMITTANCE_NUMBER }}</h2>
<h5>Date {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientRemittance->GENERATED_DATE)->format('j F, Y')  }}</h5>
<table id="invoiceInfo">
    <tr>
        <td style="text-align: left; padding-left: 0;">
            <b>Remitted to : </b> <br>
            <b>{{ $clientRemittance->client->COMPANY }}</b> <br>
            <b>{{ $clientRemittance->client->ADDRESS1 }}</b> <br>
            <b>{{ $clientRemittance->client->CITY }}, {{ $clientRemittance->client->STATE }},{{ $clientRemittance->client->ZIP }}</b><br>
            <b>{{ $clientRemittance->client->FIRSTNAME }} {{ $clientRemittance->client->LASTNAME }}</b><br>
            <b>{{ $clientRemittance->client->PHONE }}</b><br>
            <b>{{ $clientRemittance->client->EMAIL }}</b>
        </td>
    </tr>
</table>
      <h4 style="text-align: left"> Remittance Details :</h4>
      <table id="items">
          @if(isset($clientRemittance->invoice) && !empty($clientRemittance->invoice))
            <tr>
                <td>
                    Lease Number
                </td>
                <td>
                    @if(isset($clientRemittance->invoice->items) && !empty($clientRemittance->invoice->items))
                        @if(isset($clientRemittance->invoice->items[0]->item) && !empty($clientRemittance->invoice->items[0]->item))
                            @if(isset($clientRemittance->invoice->items[0]->item->assignment) && !empty(isset($clientRemittance->invoice->items[0]->item->assignment)))
                                {{ $clientRemittance->invoice->items[0]->item->assignment->lease_nmbr }}
                            @endif
                        @endif
                    @endif
                </td>
            </tr>
              <tr>
                  <td>
                      IPA Number
                  </td>
                  <td>
                      @if(isset($clientRemittance->invoice->items) && !empty($clientRemittance->invoice->items))
                          @if(isset($clientRemittance->invoice->items[0]->item) && !empty($clientRemittance->invoice->items[0]->item))
                              @if(isset($clientRemittance->invoice->items[0]->item->assignment) && !empty(isset($clientRemittance->invoice->items[0]->item->assignment)))
                                  {{ $clientRemittance->invoice->items[0]->item->assignment->assignment_id }}
                              @endif
                          @endif
                      @endif
                  </td>
              </tr>
              <tr>
                  <td>
                      Lease Company
                  </td>
                  <td>
                      @if(isset($clientRemittance->invoice->items) && !empty($clientRemittance->invoice->items))
                          @if(isset($clientRemittance->invoice->items[0]->item) && !empty($clientRemittance->invoice->items[0]->item))
                              @if(isset($clientRemittance->invoice->items[0]->item->assignment) && !empty(isset($clientRemittance->invoice->items[0]->item->assignment)))
                                  {{ $clientRemittance->invoice->items[0]->item->assignment->ls_company }}
                              @endif
                          @endif
                      @endif
                  </td>
              </tr>
              <tr>
                  <td>
                      Equipment
                  </td>
                  <td>
                      @if(isset($clientRemittance->invoice->items) && !empty($clientRemittance->invoice->items))
                          @if(isset($clientRemittance->invoice->items) && !empty($clientRemittance->invoice->items))
                             @foreach($clientRemittance->invoice->items as $i)
                                 @if(isset($i->item))
                                  <b>ITEM ID: </b>{{ $i->item['ITEM_ID'] }} <b>Serial: </b> {{ $i->item['ITEM_SERIAL'] }} <b>Make/Model: </b> {{ $i->item['ITEM_MAKE'] }} {{ $i->item['ITEM_MODEL'] }} <br>
                                  @endif
                             @endforeach
                          @endif
                      @endif
                  </td>
              </tr>
              <tr>
                  <td>
                      Gross Sales
                  </td>
                  <td>
                      @if(isset($clientRemittance->invoice) && !empty($clientRemittance->invoice))
                         ${{ round($clientRemittance->invoice->paid_amount,2) }}
                      @endif
                  </td>
              </tr>
              @if( count($clientRemittance->remittanceExpense) > 0 )
                  @foreach( $clientRemittance->remittanceExpense as $expense )
                      <tr>
                          <td>
                              {{ $expense->expenseType }}
                          </td>
                          <td>
                              ${{ round( ($expense->expenseAmount),2 ) }}
                          </td>
                      </tr>
                  @endforeach
              @else
                  <tr>
                      <td>
                          Commission Amount
                      </td>
                      <td>
                          @if(isset($clientRemittance->invoice) && !empty($clientRemittance->invoice))
                              ${{ round(($clientRemittance->invoice->paid_amount - $clientRemittance->REMITTANCE_AMT),2) }}
                          @endif
                      </td>
                  </tr>
              @endif
              <tr>
                  <td>
                      Remittance Amount
                  </td>
                  <td>
                      @if(isset($clientRemittance) && !empty($clientRemittance))
                          ${{ round($clientRemittance->REMITTANCE_AMT,2) }}
                      @endif
                  </td>
              </tr>
              <tr>
                  <td>
                      Transaction Number
                  </td>
                  <td>
                      @if(isset($clientRemittance->invoice) && !empty($clientRemittance->invoice))
                          {{ $clientRemittance->CHECKWIRENUM }}
                      @endif
                  </td>
              </tr>
          @endif
      </table>
</body>
</html>

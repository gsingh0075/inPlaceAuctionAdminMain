<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> InPlace Auction Picture Report</title>
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
         th, td {
             padding: 5px 10px;
             text-align: left;
             font-size: 12px;
         }
         /*Item Details Css */
         table#itemDetails tr:nth-child(even) {
             background-color: #eee;
         }
         table#itemDetails tr:nth-child(odd) {
             background-color: #fff;
         }
         table#itemDetails th {
             background-color: #0074bd;
             color: white;
         }
         table#itemDetails th {
             background-color: #0074bd;
             color: white;
             border: none;
         }
         /* Item Images CSS */
         table#items td{
             border: none;
         }
         table#items{
             border: none;
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
         .itemImage{
            /* width: 200px;
             height: 150px;*/
             border: 1px solid #000;
             max-width: 200px;
             width: 100%;
             height: 180px;
             object-fit: cover;
             object-position: bottom;
         }
         table#items th {
             background-color: #0074bd;
             color: white;
         }
         p{
             font-size: 12px;
         }

    </style>
</head>
<body>
<table id="top">
     <tr>
          <td colspan="3">
               <img src="{{ asset('app-assets/images/logo/pdflogo.jpg') }}" style="width:200px;height: 80px;"  alt="logo">
          </td>
          <td>
               68 South Service Road, <br> Suite 100 Melville, NY 11747 <br> Tel: 516-229-1968   Fax: 516-882-7924
          </td>
     </tr>
</table>
<table id="itemDetails" style="margin-top: 10px;">
    <tr>
        <th colspan="4" style="padding:10px; border-right: 0px solid"><b>{{ $item->ITEM_YEAR }} {{ $item->ITEM_MAKE }} {{ $item->ITEM_MODEL }} {{ $item->ITEM_SERIAL }}</b></th>
        <th colspan="1" style="padding:10px; border-left: 0px solid"><b>Effective Date:{{ $item_report_effective_date }}</b></th>
    </tr>
    <tr>
        <td colspan="2">
            <b>Lease Number</b>
        </td>
        <td colspan="1">
            @if(isset($item->assignment))
                {{ $item->assignment->lease_nmbr }}
            @endif
        </td>
        <td colspan="1">
            <b>Client Co</b>
        </td>
        <td colspan="1">
            @if(isset($item->assignment->client))
                 {{ $item->assignment->client->clientInfo->COMPANY }}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Lease Company</b>
        </td>
        <td colspan="1">
            @if(isset($item->assignment))
                {{ $item->assignment->ls_company }}
            @endif
        </td>
        <td colspan="1">
            <b>Contact Name</b>
        </td>
        <td colspan="1">
            @if(isset($item->assignment))
                {{ $item->assignment->ls_full_name }}
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Item Make</b>
        </td>
        <td colspan="1">
            {{ $item->ITEM_MAKE }}
        </td>
        <td colspan="1">
            <b>FMV</b>
        </td>
        <td colspan="1">
            ${{ round($item->FMV,2) }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Item Model</b>
        </td>
        <td colspan="1">
            {{ $item->ITEM_MODEL }}
        </td>
        <td colspan="1">
            <b>Location</b>
        </td>
        <td colspan="1">
            {{ $item->LOC_CITY }} {{ $item->LOC_STATE }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Item Serial</b>
        </td>
        <td colspan="3">
            {{ $item->ITEM_SERIAL }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Item Year</b>
        </td>
        <td colspan="3">
            {{ $item->ITEM_YEAR  }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Item Description</b>
        </td>
        <td colspan="3">
            {{ $item->ITEM_DESC }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Condition Desc</b>
        </td>
        <td colspan="3">
            {{ $item->CLIENT_COND_RPT_DESC }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Condition Code</b>
        </td>
        <td colspan="3">
            {{ $item->CONDITION_CODE }}
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Missing Items</b>
        </td>
        <td colspan="3">
            {{ $item->MISSING_ITEMS  }}
        </td>
    </tr>
    <!--<tr>
        <td colspan="2">
            <b>Additional Information</b>
        </td>
        <td colspan="3">
            {{ $item->ITEM_DESC  }}
        </td>
    </tr>-->
</table>
<p>InPlaceAuction attests this condition report to be an accurate representation and condition  description of the equipment listed above. For questions related to the content of this report, please contact InPlaceAuction.</p>
<table id="items" style="margin-top: 20px;">
    @php $totalImages = count($toBeAddedToPdf);
         $temp = 1;
    @endphp
    @for($i=0; $i<=$totalImages; $i++)
        @if($temp === 1)
            <tr>
        @endif
           @if(isset($toBeAddedToPdf[$i]['imageFileName']))
                   <td colspan="2">
                       <a href="{{ route('viewItemImage', $toBeAddedToPdf[$i]['imageId']) }}" target="_blank"><img src="{{ $toBeAddedToPdf[$i]['imageFileName'] }}" class="itemImage" alt="itemImage"></a>
                   </td>
           @endif
        <!-- Lets Break the loop -->
        @if($temp === 3)
            </tr>
            @php $temp = 0;@endphp
        @endif
        <!-- Update the Variable -->
        @php $temp++; @endphp
    @endfor
</table>
</body>
</html>

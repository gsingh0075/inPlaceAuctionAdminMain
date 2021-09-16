<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> FMV Estimate {{ $fmv->fmv_id }}</title>
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
         th, td {
              padding: 10px;
              text-align: left;
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
<table id="top">
     <tr>
          <td colspan="3">
               <img src="{{ asset('app-assets/images/logo/pdflogo.jpg') }}" style="width:200px;height: 80px;" alt="logo">
          </td>
          <td colspan="4">
               68 South Service Road, <br> Suite 100 Melville, NY 11747 <br> Tel: 516-229-1968   Fax: 516-882-7924
          </td>
     </tr>
</table>
<h2>InPlace Auction Rapid FMV</h2>
<h5>Effective {{ \Carbon\Carbon::now()->format('F j, Y') }}</h5>
<h4><b>Re: {{ $fmv->debtor_company }} (Lease : {{ $fmv->lease_number }} )</b></h4>
<h4>Purpose: @if($fmv->reason_for_fmv == 'Repo')Potential Repossession @else {{ $fmv->reason_for_fmv }} @endif</h4>
<p>Dear {{ $fmv->request_by_firstname }} {{ $fmv->request_by_lastname }},</p>
<p>InPlaceAuction is pleased to provide a value appraisal of your requested equipment. The appraisal represents our professional opinion of the Forced Liquidation Value (FLV), the Orderly Liquidation Value (OLV), and the Fair MarketValue (FMV) of the requested equipment. The FLV is the estimated gross dollar amount that could typically be realized from a properly advertised and conducted public sale, with the seller being compelled to sell with a sense of immediacy on an as-is, where is basis, as of a specific date. The OLV is similar to the FLV except that there is given a reasonable period of time to find a purchaser. The FMV is the dollar amount that may reasonably be expected for a property exchange between a willing buyer and seller with equity to both, neither under any compulsion to buy or sell, and both fully aware of all relevant facts as of a specific date</p>
     <p>This report is intended for use only by you and/or other parties chosen by you. Use of this report by others is not intended by InPlaceAuction, nor is the report intended for any other use unless express written consent is further granted. The market approach for valuation has been considered for this appraisal and has been utilized where appropriate for the value conclusions found therein. It is our opinion that the value and estimated pickup cost of the subject equipment is as follows</p>
     @if(isset($fmv->items) && !empty($fmv->items))
          @php
               $total_low_fmv_estimate = 0;
               $total_mid_fmv_estimate = 0;
               $total_high_fmv_estimate = 0;
               $total_cost_of_recovery_low = 0;
               $total_cost_of_recovery_high = 0;
          @endphp
          <table id="items">
               <tr>
                    <th colspan="3">EQUIPMENT</th>
                    <th colspan="3">VALUE ESTIMATE</th>
                    <th colspan="2">PICK UP RANGE</th>
               </tr>
               <tr class="specialHeading">
                    <td colspan="3"></td>
                    <td>FLV</td>
                    <td>OLV</td>
                    <td>FMV</td>
                    <td>Low</td>
                    <td>High</td>
               </tr>
               @foreach($fmv->items as $item)
                    @php
                       $total_low_fmv_estimate += $item->low_fmv_estimate;
                       $total_mid_fmv_estimate += $item->mid_fmv_estimate;
                       $total_high_fmv_estimate += $item->high_fmv_estimate;
                       $total_cost_of_recovery_low += $item->cost_of_recovery_low;
                       $total_cost_of_recovery_high += $item->cost_of_recovery_high;
                    @endphp
                    <tr>
                         <td colspan="3"> {{ $item->make }}, {{ $item->model }} {{ $item->equip_year }} {{ $item->ser_nmbr }} <br><br>
                          <b>Description:</b><br>
                          {{ $item->item_description }}
                         </td>
                         <td>${{ number_format($item->low_fmv_estimate,0,'.',',') }}</td>
                         <td>${{ number_format($item->mid_fmv_estimate,0,'.',',') }}</td>
                         <td>${{ number_format($item->high_fmv_estimate,0,'.',',') }}</td>
                         <td>${{ number_format($item->cost_of_recovery_low,0,'.',',') }}</td>
                         <td>${{ number_format($item->cost_of_recovery_high,0,'.',',') }}</td>
                    </tr>
               @endforeach
               <tr>
                  <td colspan="3">
                       <b>TOTAL</b>
                  </td>
                  <td><b>${{ number_format($total_low_fmv_estimate,0,'.',',') }}</b></td>
                  <td><b>${{ number_format($total_mid_fmv_estimate,0,'.',',') }}</b></td>
                  <td><b>${{ number_format($total_high_fmv_estimate,0,'.',',') }}</b></td>
                  <td><b>${{ number_format($total_cost_of_recovery_low,0,'.',',') }}</b></td>
                  <td><b>${{ number_format($total_cost_of_recovery_high,0,'.',',') }}</b></td>
               </tr>
          </table>
          <p style="font-size: 10px;"><i>* denotes analysis not performed or value/cost is $0. When there are multiple items with no pickup costs, please review additional comments area.</i></p>
          @if(isset($fmv->comments) && !empty($fmv->comments))
               <p><b>Additional Comments: </b>{{ $fmv->comments }}</p>
          @endif
     @endif
     <p>The fees charged for this appraisal were not contingent on the values reported nor were any undisclosed fees,commissions, or other compensation received. Our liability for loss, if any, arising from the services we provided shall not exceed our collected fee.</p>
     <p>Yours Truly, <br>
        <b>Edward Castagna
        <br>Senior Appraiser, InPlaceAuction LLC.
        <br> 516.229.1968</b>
     </p>
</body>
</html>

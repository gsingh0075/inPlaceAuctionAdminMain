<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> IPA Pickup Authorization {{ $authorization->contractor_auth_id }}</title>
    <style type="text/css">
         body{
           font-size: 13px;
           line-height: 18px;
         }
         h3{
             text-align: center;
             margin: 5px 0;
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
         table#AssignedInfo{
             margin-top: 10px;
             border: none;
         }
         table#AssignedInfo td{
             border: none;
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
<table id="top" style="border-bottom: 3px solid #000; margin-bottom: 15px;">
     <tr>
          <td colspan="3">
               <img src="{{ asset('app-assets/images/logo/pdflogo.jpg') }}" style="width:200px;height: 80px;"  alt="logo">
          </td>
          <td colspan="4">
               68 South Service Road,<br>Suite 100 Melville, NY 11747 <br> Tel: 516-229-1968  Fax: 516-882-7924
          </td>
     </tr>
</table>
<h2>InPlace Auction Authorization #{{ $authorization->contractor_auth_id }}</h2>
<h5>Date {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $authorization->create_dt)->format('j F, Y')  }}</h5>
<table id="AssignedInfo">
    <tr style="vertical-align: top;">
        <td colspan="3" style="padding-left: 0;">
            <h4 style="text-align: left;">Assigned to : </h4>
            {{ $authorization->contractor->first_name }} {{ $authorization->contractor->last_name }} <br>
            {{ $authorization->contractor->company }} <br>
            {{ $authorization->contractor->address1 }} <br>
            {{ $authorization->contractor->city }}, {{ $authorization->contractor->state }},{{ $authorization->contractor->zip1 }}<br>
            {{ $authorization->contractor->phone }}<br>
            {{ $authorization->contractor->email }}
       </td>
       <td colspan="4">
           <h4 style="text-align: left;">Lease Information :</h4>
           {{ $assignment->ls_full_name }} <br>
           {{ $assignment->ls_company }} <br>
           {{ $assignment->ls_address1 }} <br>
           {{ $assignment->ls_city }}, {{ $assignment->ls_state }}, {{ $assignment->ls_zip }}<br>
           {{ $assignment->ls_buss_phone }}
        </td>
    </tr>
</table>
      <h4 style="text-align: left;"> Equipments :</h4>
        <table id="items" style="margin-bottom: 10px;">
            <tr>
                <th>Item#</th>
                <th>Make</th>
                <th>Model</th>
                <th>Serial#</th>
                <th>Year</th>
                <th>Location</th>
            </tr>
            @if(isset($authorization->authItems) &&! empty($authorization->authItems))
                @foreach( $authorization->authItems as $item)
                    <tr>
                        <td>
                            {{ $item->item->ASSIGNMENT_ID }}-{{ $item->item->ITEM_NMBR }}
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
                            {{ $item->item->LOC_CITY }} {{ $item->item->LOC_STATE }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>

     @if(isset($authorization->special_instructions) && !empty($authorization->special_instructions))
     <h4 style="text-align: left;"> Special Instructions :</h4>
     <p>{{ $authorization->special_instructions }}</p>
     @endif

     @if(isset($authorization->add_info1) && !empty($authorization->add_info1))
     <h4 style="text-align: left;"> Additional Information :</h4>
     <p>{{ $authorization->add_info1 }}</p>
     @endif

     @if(isset($authorization->terms) && !empty($authorization->terms))
        <h4 style="text-align: left;"> Additional Terms :</h4>
        <p>{{ $authorization->terms }}</p>
     @endif

     <h4 style="text-align: left;"> Terms As Agreed :</h4>
     <p>This is your authorization to act as our agents to collect or repossess the above collateral. We agree to indemnify and hold you harmless from and against any and all claims, damages, losses and actions, including reasonable attorneyfees, resulting from and arising out of your efforts to collect and/or repossess the above collateral, except how ever such as may be caused by or arise out of negligence or unauthorized act on the part of you, your company, its officers, employees or agents.</p>
     <p>
     <b>Edward Castagna
     <br>CEO, InPlaceAuction LLC.
     <br> 516.229.1968</b>
     </p>
</body>
</html>

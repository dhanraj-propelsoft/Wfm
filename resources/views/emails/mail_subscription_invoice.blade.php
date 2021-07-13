<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
    .invoice-box{
        font-size:12px;
        max-width:600px;
        margin:auto;
        padding:30px;
        border:1px solid #eee;
        line-height:24px;
        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color:#555;
    }
    
    .invoice-box table{
        width:100%;
        line-height:inherit;
        text-align:left;
    }
    
    .invoice-box table td{
        padding:5px;
        vertical-align:top;
    }
    
    .invoice-box table tr td:nth-child(2){
        text-align:right;
    }
    
    .invoice-box table tr.top table td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.top table td.title{
        line-height:45px;
        color:#333;
    }
    
    .invoice-box table tr.heading td{
        background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;
    }
    
    .invoice-box table tr.details td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom:1px solid #eee;
    }
    
    .invoice-box table tr.item.last td{
        border-bottom:none;
    }
    
    .invoice-box table tr.total td:nth-child(2){
        border-top:2px solid #eee;
        font-weight:bold;
    }
    .second
    {

        text-align: left;
    }
    .third
    {

        text-align: right;
    }

    @media print {
        .invoice-box {
            border:0;
        }
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ $message->embed(public_path().'/logo.png') }}"  style="width:75%; max-width:180px;">
                            </td>
                            <td style="float: right;"><strong>For Enquiries :</strong><br>
                               Phone : 0431 - 4000255 &nbsp; <br>
                                Email : info@propelsoft.in<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td><strong><p>Billing Address :</p></strong>
                                {{ $address }}<br>
                                {{ $city }}<br>
                                {{ $state }} - {{ $pin }}
                            </td>
                            
                            <td style="float: right;">
                                Order# : {{ $order_id }}<br>
                                Added Date : {{ $added_on }}<br>
                                Expiry Date : {{ $expire_on }}<br>
                                
                            </td>
                             
                        </tr>
                    </table>
                </td>
            </tr>
            </table>

            <table>
          
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td class="third">
                    Price
                </td>
            </tr>

            <tr class="item">
                <td>
                <span style="font-size: 12px; width: 100%; float: left;">{{ $item }}</span>
                
                 </td>
                <td class="third">
                    Rs. {{ $total_price }}
                </td>
            </tr>             
            
                      
        </table>

    </div>
</body>
</html>

<?php

namespace App\Http\Controllers;

use App\Models\ProductOrderModel;
use App\Models\SettingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductOrderController extends Controller
{
    function ProductOrderPage(){
        return view('Order.ProductOrder');
    }

    function ProductOrderData(){
        $result=DB::table('product_order')
            ->select('invoice_no','total_price','mobile','name','payment_method','delivery_address','city','delivery_charge','order_date','order_time','order_status')
            ->groupBy('invoice_no')
            ->orderBy('id','desc')
            ->get();
        return $result;
    }

    function ProductOrderDetailsData(Request $request){
        $invoice_no=$request->input('invoice_no');
        $result=ProductOrderModel::where('invoice_no','=',$invoice_no)->get();
        return $result;
    }

    function ProductOrderDelete(Request $request){
        $id=$request->input('id');
        $result=ProductOrderModel::where('invoice_no','=',$id)->delete();
        if ($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }

    function ProductOrderStatusEdit(Request $request){
        $statusID=$request->input('statusID');
        $OrderStatus=$request->input('status');

        $ProductOrderData= ProductOrderModel::where('invoice_no',$statusID)->get();
        $phone=$ProductOrderData[0]['mobile'];
        $six_digit_random_number = mt_rand(100000, 999999);

        //Api setup
        $settings=SettingsModel::all('ssl_wireless_sms_api_token', 'ssl_wireless_sms_sid', 'ssl_wireless_sms_domain');
        $API_TOKEN=$settings[0]['ssl_wireless_sms_api_token'];
        $SID=$settings[0]['ssl_wireless_sms_sid'];
        $DOMAIN=$settings[0]['ssl_wireless_sms_domain'];

        if ($OrderStatus=='Rejected'){
            //message text
            $msisdn = $phone;
            $messageBody = "Dear Customer, Your order has been canceled. Helpline: 09678221062. For more orders please visit www.kistimath.com";
            $csmsId = $six_digit_random_number; // csms id must be unique for one day , max length 20

            $params = [
                "api_token" => $API_TOKEN,
                "sid" => $SID,
                "msisdn" => $msisdn,
                "sms" => $messageBody,
                "csms_id" => $csmsId
            ];
            $url = trim($DOMAIN, '/')."/api/v3/send-sms";
            $params = json_encode($params);

            //curl code
            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params),
                'accept:application/json'
            ));

            $response = curl_exec($ch);

            curl_close($ch);

            $status=(json_decode($response,true))['status'];
            if($status=="SUCCESS"){
                $result=ProductOrderModel::where('invoice_no',$statusID)->update([
                    'order_status'=>$OrderStatus
                ]);
                return $result;
            }
            else{
                return  0;
            }


        }
        else if ($OrderStatus=='Delivered'){
            //message text
            $msisdn = $phone;
            $messageBody = "Dear Customer, Your order successfully delivered. Helpline: 09678221062. For more orders please visit www.kistimath.com";
            $csmsId = $six_digit_random_number; // csms id must be unique for one day , max length 20

            $params = [
                "api_token" => $API_TOKEN,
                "sid" => $SID,
                "msisdn" => $msisdn,
                "sms" => $messageBody,
                "csms_id" => $csmsId
            ];
            $url = trim($DOMAIN, '/')."/api/v3/send-sms";
            $params = json_encode($params);

            //curl code
            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params),
                'accept:application/json'
            ));

            $response = curl_exec($ch);

            curl_close($ch);

            $status=(json_decode($response,true))['status'];
            if($status=="SUCCESS"){
                $result=ProductOrderModel::where('invoice_no',$statusID)->update([
                    'order_status'=>$OrderStatus
                ]);
                return $result;
            }
            else{
                return  0;
            }
        }
        else{
            $result=ProductOrderModel::where('invoice_no',$statusID)->update([
                'order_status'=>$OrderStatus
            ]);
            return $result;
        }



    }

    function ProductOrderInvoiceData(Request $request){
        $id=$request->input('id');
        $OrderData=ProductOrderModel::where('invoice_no','=',$id)->get();
        $sub_total=0;
        foreach ($OrderData as $OrderDatas){
            $total_price_taka=$OrderDatas['total_price'];
            $price_array=explode(" ",$total_price_taka);
            $total_price=$price_array[0];
            $sub_total +=$total_price;
        }
        $result=['order_data'=>$OrderData,'sub_total'=> $sub_total];
        return json_encode($result);
    }
}

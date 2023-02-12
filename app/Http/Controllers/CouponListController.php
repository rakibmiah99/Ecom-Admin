<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductListModel;
use App\Models\SubCategoryModel;
use App\Models\CouponListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CouponListController extends Controller
{
    function CouponListPage(){
        return view('Coupon.CouponList');
    }

    function CouponListData(){
        $result= CouponListModel::orderBy('id','desc')->get();
        return $result;
    }

    function CouponListSingleData(Request  $request){
        $id = $request->id;

        $result= CouponListModel::where('id','=',$id)->get()[0];
        return $result;
    }

    function CouponListAdd(Request $request){
        date_default_timezone_set('Asia/Dhaka');

        $couponName = $request->couponName;
        $minimumOrderCondition = $request->minimumOrderCondition;
        $couponAmount = $request->couponAmount;
        $couponStatus = $request->couponStatus;
        $createdDate = date('Y-m-d');
        $createdTime = date('h:i:s');

        $result=CouponListModel::insert([
            'cupon_name' => $couponName,
            'minimun_order_condition' => $minimumOrderCondition,
            'cupon_amount' => $couponAmount,
            'cupon_status' => $couponStatus,
            'created_date' => $createdDate,
            'created_time' => $createdTime
        ]);
        if ($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }

    function CouponListUpdate(Request $request){
        date_default_timezone_set('Asia/Dhaka');
        $id = $request->editID;
        $couponName = $request->couponName;
        $minimumOrderCondition = $request->minimumOrderCondition;
        $couponAmount = $request->couponAmount;
        $couponStatus = $request->couponStatus;
        //$createdDate = date('Y-m-d');
        //$createdTime = date('h:i:s');

        $result=CouponListModel::where('id','=',$id)->update([
            'cupon_name' => $couponName,
            'minimun_order_condition' => $minimumOrderCondition,
            'cupon_amount' => $couponAmount,
            'cupon_status' => $couponStatus,
//            'created_date' => $createdDate,
//            'created_time' => $createdTime
        ]);
        if ($result==true){
            return 1;
        }
        else{
            return 0;
        }
    }


    function CouponListDelete(Request $request){
        $id = $request->input('id');
        $result = CouponListModel::where('id','=',$id)->delete();
        if ($result == true){
            return 1;
        }
        else{
            return 0;
        }
    }

}

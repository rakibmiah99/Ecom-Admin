@extends('Layout.app')
@section('content')
    @include('Component.LoadingDiv')
    @include('Component.WentWrongDiv')



    <div id="MainDiv" class="container d-none">
        @include('Component.addNewBtn')
        <div class="row">
            <div class="col-md-12 data-table-card col-lg-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <table id="SiteDataTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Min. Order Condition</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Active</th>
                            </tr>
                            </thead>
                            <tbody id="SiteDataTableBody">
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="AddNewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="exampleModalLabel">Add New Coupon</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Coupon Name</label>
                                <input id="couponName" class="form-control" type="text">
                            </div>
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Minimum Order Condition</label>
                                <input id="minimumOrderCondition" class="form-control" type="text">
                            </div>
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Coupon Amount</label>
                                <input id="couponAmount" class="form-control" type="text">
                            </div>
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Coupon Status</label>
                                <input id="couponStatus" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="ModalCloseBtn" type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    <button id="addCouponBtn" type="button" class="btn btn-dark">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="CouponEditModal" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" id="exampleModalLabel1">Edit Coupon</p>
                    <span class="d-none" id="CatID"></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Coupon Name</label>
                                <input id="couponName" class="form-control" type="text">
                            </div>
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Minimum Order Condition</label>
                                <input id="minimumOrderCondition" class="form-control" type="text">
                            </div>
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Coupon Amount</label>
                                <input id="couponAmount" class="form-control" type="text">
                            </div>
                            <div class="col-md-12 col-sm-12 col-6">
                                <label>Coupon Status</label>
                                <input id="couponStatus" class="form-control" type="text">
                           </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="" type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    <button id="EditCouponBtn" type="button" class="btn btn-dark">Add</button>
                </div>
            </div>
        </div>
    </div>
    @include('Component.DeleteModal')
    @include('Component.ChangeImageModal')
@endsection

@section('script')
    <script>
        var  ActionSpinnerBtn="<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Processing..";

        DataList()


        //Add New Coupon
        $('#addCouponBtn').on('click',function(){
            $('#addCouponBtn').html(ActionSpinnerBtn);
            let ConfirmBtn=$(this);
            ConfirmBtn.prop("disabled", true);

            let URL = "/CouponListAdd";
            let couponName = $('#AddNewModal #couponName').val();
            let minimumOrderCondition = $('#AddNewModal #minimumOrderCondition').val();
            let couponAmount = $('#AddNewModal #couponAmount').val();
            let couponStatus = $('#AddNewModal #couponStatus').val();

            if(couponName.length > 0){
                if(minimumOrderCondition.length > 0){
                    if(couponAmount.length > 0){
                        if(couponStatus.length > 0){
                            axios.post(URL,{
                                couponName: couponName,
                                minimumOrderCondition: minimumOrderCondition,
                                couponAmount: couponAmount,
                                couponStatus: couponStatus
                            }).then(function(response){
                                if(response.status === 200){
                                    ConfirmBtn.html("CONFIRM & SAVE");
                                    ConfirmBtn.prop("disabled", false);

                                    SuccessToast("Request Success");
                                    $('#AddNewModal #couponName').val('');
                                    $('#AddNewModal #couponStatus').val('');
                                    $('#AddNewModal #minimumOrderCondition').val('');
                                    $('#AddNewModal #couponAmount').val('');
                                    $('#AddNewModal').modal('hide');
                                    $('.close').trigger('click')
                                    DataList();
                                }else{
                                    ErrorToast("Failed ! Please Try Again");
                                }
                            }).catch(function(err){
                                ErrorToast("Something went wrong !");
                            })
                        }else{
                            ErrorToast("Coupon Status is required")
                        }
                    }else{
                        ErrorToast("Coupon Amount is required")
                    }
                }else{
                    ErrorToast("Minimum Order Condition is required")
                }
            }else{
                ErrorToast("Coupon Name is required")
            }
        })


        //Delete Existing Coupon
        $('#SiteDataTableBody').on('click','.deleteItem',function (){
            let id=$(this).data('id');
            $('#DeleteID').html(id);
            $('#DeleteModal').modal('show');
        })

        //Set Data To Edit Modal
        $('#SiteDataTableBody').on('click','.editCoupon',function (){
            let couponName = $('#CouponEditModal #couponName');
            let couponAmount = $('#CouponEditModal #couponAmount');
            let couponStatus = $('#CouponEditModal #couponStatus');
            let minimumOrderCondition = $('#CouponEditModal #minimumOrderCondition');
            let id=$(this).data('id');
            let  URL = '/CouponListSingleData';

            axios.post(URL,{id: id}).then(function (response){
                if(response.status === 200){
                    let d = response.data;
                    couponStatus.val(d.cupon_status)
                    couponName.val(d.cupon_name)
                    couponAmount.val(d.cupon_amount)
                    minimumOrderCondition.val(d.minimun_order_condition);
                    $('#CouponEditModal #EditCouponBtn').attr('edit-id',d.id);
                }else{
                    ErrorToast("Failed ! Please Try Again");
                }
            }).catch(function (err){
                console.log(err)
                ErrorToast('Something Went Wrong. Please Try Again.')
            })
        })

        $('#DeleteConfirm').click(function (event) {
            let deleteID= $('#DeleteID').html();

            let DeleteBtn=$('#DeleteConfirm');
            CouponDelete(deleteID,DeleteBtn);
        });

        function CouponDelete(deleteID,DeleteBtn){
            DeleteBtn.html(ActionSpinnerBtn);

            let URL="/CouponListDelete";
            let AxiosConfig = { headers: { 'Content-Type': 'application/json' } };
            let blankData=" ";

            axios.post(URL,{id:deleteID},AxiosConfig).then(function (response) {

                DeleteBtn.html("Yes");

                if (response.data===1) {
                    $('#DeleteConfirm').attr('data-id',blankData);
                    $('#DeleteModal').modal('hide');
                    toastr.success("Deleted !");
                    DataList();
                }
                else {
                    $('#DeleteModal').modal('hide');
                    toastr.error("Failed ! Please Try Again");
                }

            }).catch(function (error) {
                DeleteBtn.html("Yes");
                $('#DeleteModal').modal('hide');
                toastr.error("Something Went Wrong");
            });
        }


        //Edit Existing Coupon
        $('#CouponEditModal #EditCouponBtn').click(function (){
            let editID = $(this).attr('edit-id');
            let URL = '/CouponListUpdate';
            let couponName = $('#CouponEditModal #couponName').val();
            let minimumOrderCondition = $('#CouponEditModal #minimumOrderCondition').val();
            let couponAmount = $('#CouponEditModal #couponAmount').val();
            let couponStatus = $('#CouponEditModal #couponStatus').val();

            if(couponName.length > 0){
                if(minimumOrderCondition.length > 0){
                    if(couponAmount.length > 0){
                        if(couponStatus.length > 0){
                            $('#EditCouponBtn').html(ActionSpinnerBtn);
                            let ConfirmBtn=$(this);
                            ConfirmBtn.prop("disabled", true);
                            axios.post(URL,{
                                editID: editID,
                                couponName: couponName,
                                minimumOrderCondition: minimumOrderCondition,
                                couponAmount: couponAmount,
                                couponStatus: couponStatus
                            }).then(function(response){
                                if(response.status === 200){
                                    ConfirmBtn.html("CONFIRM & SAVE");
                                    ConfirmBtn.prop("disabled", false);

                                    SuccessToast("Request Success");
                                    $('#CouponEditModal #couponName').val('');
                                    $('#CouponEditModal #couponStatus').val('');
                                    $('#CouponEditModal #minimumOrderCondition').val('');
                                    $('#CouponEditModal #couponAmount').val('');
                                    $('#CouponEditModal').modal('hide');
                                    $('.close').trigger('click')
                                    DataList();
                                }else{
                                    ConfirmBtn.html("CONFIRM & SAVE");
                                    ConfirmBtn.prop("disabled", false);
                                    ErrorToast("Failed ! Please Try Again");
                                }
                            }).catch(function(err){
                                ConfirmBtn.html("CONFIRM & SAVE");
                                ConfirmBtn.prop("disabled", false);
                                ErrorToast("Something went wrong !");
                            })
                        }else{
                            ErrorToast("Coupon Status is required")
                        }
                    }else{
                        ErrorToast("Coupon Amount is required")
                    }
                }else{
                    ErrorToast("Minimum Order Condition is required")
                }
            }else{
                ErrorToast("Coupon Name is required")
            }
        })









        function DataList(){
            let URL="/CouponListData";
            axios.get(URL).then(function (response) {
                console.log(response)
                if(response.status==200){
                    $('#LoadingDiv').addClass('d-none');
                    $('#MainDiv').removeClass('d-none')
                    $('#SiteDataTable').DataTable().destroy();
                    $('#SiteDataTableBody').empty();
                    response.data.forEach(function (item,index){

                        let tableRow = `
                            <tr>
                                <td>${index+1}</td>
                                <td>${item.cupon_name}</td>
                                <td>${item.minimun_order_condition}</td>
                                <td>${item.cupon_amount}</td>
                                <td>${item.cupon_status}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Dropdown button</button>
                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                            <a class='dropdown-item deleteItem' data-id="${item.id}"  href='#'>Delete Coupon</a>
                                            <a class='dropdown-item editCoupon' data-id="${item.id}" data-toggle="modal" data-target="#CouponEditModal"   href='#'>Edit Coupon</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `

                        $('#SiteDataTableBody').append(tableRow);
                    });





                    $('#SiteDataTable').DataTable({
                        "order":false,
                        "lengthMenu": [5, 10, 20, 50]
                    });
                    $('.dataTables_length').addClass('bs-select');
                }
                else{
                    $('#LoadingDiv').addClass('d-none')
                    $('#WentWrongDiv').removeClass('d-none')
                }

            }).catch(function (error) {
                $('#LoadingDiv').addClass('d-none')
                $('#WentWrongDiv').removeClass('d-none')
            });
        }






    </script>
@endsection

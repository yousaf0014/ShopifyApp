@extends('layouts.customer.app')
@section('content')
<style type="text/css">
.boxColor {
  width: 20px;
  height: 20px;
  margin: 5px;
  border: 1px solid rgba(0, 0, 0, .2);
  display: inline-table;
}
</style>

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Order {{$order->id}}</h6>
                            
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                    <form id="general-info" class="section general-info">
                        <div class="info">
                            <div class="row">
                                <div class="widget-content widget-content-area col-xl-12 col-lg-12 col-md-12">
                                    <ul class="nav nav-tabs  mb-3 mt-3" id="simpletab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Summary</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#status" role="tab" aria-controls="contact" aria-selected="false">Status</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#images" role="tab" aria-controls="contact" aria-selected="false">Images</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#billing" role="tab" aria-controls="contact" aria-selected="false">Billing</a>
                                        </li>
                                        <li class="nav-item" style="display: none;">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#history" role="tab" aria-controls="contact" aria-selected="false">History</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#shipment" role="tab" aria-controls="contact" aria-selected="false">Shipment</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="simpletabContent">
                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <h2>Order Summary</h2>
                                            <div class="row">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                                                    <div class="widget widget-card-one">
                                                        <div class="widget-content">
                                                            <h3 class="ml-2 pt-2">Details</h3>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover mb-4">
                                                                    <tr>
                                                                        <td>Statu</td>
                                                                        <td style="text-transform: capitalize;">{{$order->status}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Charge</td>
                                                                        <td>{{$order->charge}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Shipment Total</td>
                                                                        <td>{{$order->shipment + $order->additional_shipment}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Order Total</td>
                                                                        <td>{{$order->order_total}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Items</td>
                                                                        <td>{{$order->items}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Quantity</td>
                                                                        <td>{{$order->quantity}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Date</td>
                                                                        <td>{{date('M d,Y',strtotime($order->order_date))}}</td>
                                                                    </tr>

                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                                                    <div class="widget widget-card-one">
                                                        <div class="widget-content">
                                                            <h3 class="ml-2 pt-2">Partner Info</h3>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover mb-4">
                                                                    <tr>
                                                                        <td>Name</td>
                                                                        <td style="text-transform: capitalize;">{{$partner->name}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Email</td>
                                                                        <td>{{$partner->email}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Currency</td>
                                                                        <td>{{$partner->currency}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Type</td>
                                                                        <td style="text-transform: capitalize;">{{$partner->type}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Shopify Order ID</td>
                                                                        <td>{{$order->shopify_order_id}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Join Date</td>
                                                                        <td>{{date('M d,Y',strtotime($partner->created_at))}}</td>
                                                                    </tr>

                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                                                    <div class="widget widget-card-one">
                                                        <div class="widget-content">
                                                            <h3 class="ml-2 pt-2">Shipping Address</h3>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover mb-4">
                                                                    <tr>
                                                                        <td>Name</td>
                                                                        <td style="text-transform: capitalize;">{{$order->orderShipment->name}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Address</td>
                                                                        <td>{{$order->orderShipment->address1}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Address 2</td>
                                                                        <td>{{$order->orderShipment->address2}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>City</td>
                                                                        <td>{{$order->orderShipment->city}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Zip</td>
                                                                        <td>{{$order->orderShipment->zip}}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td>Province</td>
                                                                        <td>{{$order->orderShipment->province}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Country</td>
                                                                        <td>{{$order->orderShipment->country}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Email</td>
                                                                        <td>{{$order->email}}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="status" role="tabpanel" aria-labelledby="profile-tab">
                                            <h2>Change Status</h2>
                                            <div class="row">
                                                <?php foreach($order->orderItem as $item){ ?>
                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-3 layout-spacing">
                                                        <div class="widget widget-card-one">
                                                            <div class="widget-content">
                                                                <h6 class="ml-2 pt-2">{{$item->name}}</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hover mb-4">
                                                                        <tr>
                                                                            <td>
                                                                                <div class="n-chk">
                                                                                    <label class="new-control new-checkbox checkbox-primary">
                                                                                      <input type="checkbox" class="new-control-input" onchange="changeStatus('{{$item->id}}')">
                                                                                      <span class="new-control-indicator"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <?php 
                                                                                $img = $userProducts[$item->id]->productPrintingGroupOption[0]->shirt_design;?>

                                                                                <img src="{{url('/').'/../storage/app/public/uploads/'.$img}}" width="200px">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="text-transform:capitalize;">
                                                                                Status:<span style="text-transform:capitalize;">{{$item->status}}</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Quantity:{{$item->quantity}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>{{$item->title}}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="profile-tab2">
                                            <h2>Order Items & Images</h2>
                                            <?php foreach($order->orderItem as $index=>$item){ 
                                                $userProduct = $userProducts[$item->id];
                                                $color = '';
                                                ?>
                                                <div class="p-2 mt-4" style="border: 1px solid white">
                                                    <h4>Item{{$index+1}}</h4>
                                                    <div class="row">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing">
                                                            <h6 class="pt-2 ml-2">Admin Product</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover mb-4">
                                                                    <tr>
                                                                        <td>Product Sku</td>
                                                                        <td>{{$userProduct->adminProduct->sku}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Product Title</td>
                                                                        <td>{{$userProduct->adminProduct->name}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Product Supplier Code</td>
                                                                        <td>{{$userProduct->adminProduct->supplier_code}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Pic</td>
                                                                        <td><img src="{{url('/').'/../storage/app/public/uploads/'.$userProduct->adminProduct->product_pic}}" width="200px"></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 layout-spacing">
                                                            <h6 class="pt-2 ml-2">Partner Item Details</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover mb-4">
                                                                    <tr>
                                                                        <td>Sku</td>
                                                                        <td>{{$item->sku}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Title</td>
                                                                        <td>{{$item->name}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Quantity</td>
                                                                        <td>{{$item->quantity}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Status</td>
                                                                        <td style="text-transform:capitalize;">{{$item->status}}</td>
                                                                    </tr>
                                                                    <?php $attributes = json_decode($item->attributes);
                                                                        foreach($attributes as $name=>$val){
                                                                    ?>
                                                                        <tr>
                                                                            <td style="text-transform: capitalize;">
                                                                                {{$name}}          
                                                                            </td>
                                                                            <td>
                                                                              <?php  if(strtolower($name) == 'color'){
                                                                                $color = $val;
                                                                                $code = getColorHash($userProduct->admin_product_id,$val); ?>
                                                                                     {{$val}}&nbsp;&nbsp;
                                                                                    <?php if(!empty($code)){?>
                                                                                        <div class="boxColor" style="background: #{{$code}}"></div>
                                                                                    <?php } ?>
                                                                                <? }else{ ?>
                                                                                    {{$val}}
                                                                                <?php } ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <?php foreach($userProduct->productPrintingGroupOption as $itemImages){ ?>
                                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-3 layout-spacing">
                                                                <div class="widget widget-card-one">
                                                                    <div class="widget-content">
                                                                        <h6 class="ml-2 pt-2">{{$itemImages->name}}</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-hover mb-4">
                                                                                <tr>
                                                                                    <td>Art Work</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <?php $artwork = getArtWork($item->user_product_id,$color)?>
                                                                                    <td style="text-align: center;">
                                                                                        <?php
                                                                                        $img = '';
                                                                                        $img = $itemImages->artwork;
                                                                                        if(!empty($artwork) && $artwork == 'dark'){
                                                                                            $img = $itemImages->artwork_dark;
                                                                                        }
                                                                                        ?>
                                                                                        <img src="{{url('/').'/../storage/app/public/uploads/'.$img}}" width="200px">
                                                                                        <a class="btn btn-primary mt-3" href="{{url('/').'/../storage/app/public/uploads/'.$img}}" target="_blank">
                                                                                            Download
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Mockup</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="text-align: center;">
                                                                                        <?php $img1 = $itemImages->shirt_design;?>

                                                                                        <img src="{{url('/').'/../storage/app/public/uploads/'.$img1}}" width="200px">
                                                                                        <a class="btn btn-primary mt-3" href="{{url('/').'/../storage/app/public/uploads/'.$img1}}" target="_blank">
                                                                                            Download
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> 
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            
                                        </div>
                                        <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="contact-tab">
                                            <h2>Payment</h2>
                                            <?php if(!empty($order->orderPayment->id)){ ?>
                                                <div id="general-info" class="section general-info">
                                                    <div class="info">
                                                        <h6 class="">Payment</h6>
                                                            <div class="row">
                                                                <div class="widget-content widget-content-area">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered table-hover mb-4">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-center">Order</th>
                                                                                    <th class="text-center">Payment</th>
                                                                                    <th class="text-center">Date</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="text-center">{{$order->id}}</td>
                                                                                    <td>{{$order->orderPayment->amount}} AUD</td>
                                                                                    <td>{{Date('M d,Y',strtotime($order->orderPayment->created_at))}}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="contact-tab">
                                            <p class="">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="shipment" role="tabpanel" aria-labelledby="contact-tab">
                                            <p class="">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#myModal").on("show.bs.modal", function(e) {
            url =  $(e.relatedTarget).data('target-url');
            $.get( url , function( data ) {
                $(".modal-body").html(data);
            });

        });
    });
    function show_alert(id) {
        if(confirm('Are you sure? you want to delete.')){
            $('#delete_'+id).submit();
        }else{
            return false;
        }
    }
</script>
@endsection
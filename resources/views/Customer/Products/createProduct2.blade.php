@extends('layouts.customer.app')
@section('content')

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Prodduct Form Add (Step 3)</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => 'storeProductsStep3/'.$adminProduct->id,'id'=>'add_product','name'=>'add_product','class'=>'form-horizontal','enctype'=>'multipart/form-data')) !!}
                            <div class="row">
                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                    <div class="layout-px-spacing">
                                        <div class="row layout-top-spacing ">
                                            <div class="widget widget-chart-two">
                                                <div class="widget-heading" style="text-align: center;">
                                                    <h2 style="margin:auto;" class="">{{$adminProduct->name}}</h2>
                                                </div>
                                                <div class="widget-content mt-4" style="position: relative;">
                                                    <?php if(!empty($adminProduct->product_pic)){ ?>
                                                        <!--<img src="{{Storage::disk('public')->url('uploads/'.$adminProduct->product_pic)}}" width="200px"> -->
                                                        <img src="{{url('/').'/../storage/app/public/uploads/'.$adminProduct->product_pic}}" width="100%">
                                                    <?php } ?>
                                                    <div class="mt-3">
                                                        <a class="btn btn-primary" href="{{url('/').'/../storage/app/public/uploads/'.$adminProduct->product_pic}}" target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                            <span>Download Image</span>
                                                        </a>
                                                    </div>
                                                    <div class="mt-4" id="price_div">
                                                        <?php $localPrice  = localAmount($adminProduct->price); ?>
                                                        <div class="mt-3">Vendor Price:<span id="base_amount" title="{{$localPrice['amount']}}"> {{$localPrice['amount'].' ('.$localPrice['currency'].')'}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="formGroupExampleInput">User Profit</label>
                                                        <input required type="text" id="profit" class="form-control input-sm" name="profit" value="" onchange="calctotal()" >
                                                    </div>
                                                    <div class="form-group mb-4 mt-4">
                                                        <input type="hidden" name="base_price" value="{{$localPrice['amount']}}">
                                                        Product Total:<span id="total" title="{{$localPrice['amount']}}">{{$localPrice['amount']}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-7">
                                    <div class="container">
                                        <div class="layout-px-spacing">
                                            <div class="row layout-top-spacing">
                                                <div class="mb-4">
                                                    <h2>Prodcut Attributes</h2>
                                                </div>
                                              <?php foreach($productPrintingGroups as $group){ ?>
                                                    <div class="col-lg-12 form-group mb-4 mt-4">
                                                        <div class="mb-4">
                                                            <h3 style="text-transform: capitalize;">{{$group->name}}</h3>
                                                        </div>
                                                    </div>
                                                        <?php foreach($group->attribute as $attr){ ?>
                                                            <div class="col-lg-12 form-group mb-4 mt-4">
                                                                <div class="n-chk">
                                                                    <label class="new-control new-checkbox checkbox-primary">
                                                                      <input type="checkbox" class="new-control-input" name="printing[{{$group->id}}][]" onclick="changeStatus('input{{$attr->id}}','{{$attr->id}}','{{$attr->name}}')" value="{{$attr->id}}" id="group_{{$attr->id}}" >
                                                                      <span class="new-control-indicator"></span>
                                                                      <?php $localPrice  = localAmount($attr->amount); ?>
                                                                      <span style="text-transform: capitalize;">{{$attr->name}}</span>  (Additional Charges:{{$localPrice['amount']}} ({{$localPrice['currency']}})
                                                                      <input type="hidden" name="amount[{{$attr->id}}]" id="price_{{$attr->id}}" value="{{$localPrice['amount']}}" disabled="" class="input{{$attr->id}}">
                                                                    </label>
                                                                </div>

                                                                <div class="form-row mb-4">
                                                                    <div class="form-group col-md-4">
                                                                        <label for="inputEmail4">Mockup</label>
                                                                        <input type="file" class="input{{$attr->id}} form-control" name="shirtdesign[{{$attr->id}}]" disabled="">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="inputPassword4">Art work Light</label>
                                                                        <input type="file" required class="input{{$attr->id}} form-control" name="art_light[{{$attr->id}}]" disabled="">
                                                                    </div>
                                                                    <div class="form-group col-md-4">
                                                                        <label for="inputPassword4">Art work Dark</label>
                                                                        <input type="file" required class="input{{$attr->id}} form-control" name="art_dark[{{$attr->id}}]" disabled="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-lg-12 form-group mb-4 mt-4">
                                                <div class="mb-4">
                                                    <h3 style="text-transform: capitalize;">Associate Color To clip Art</h3>
                                                </div>
                                            </div>
                                                    
                                                            
                                            <?php foreach($colorData as $color){ ?>
                                                <div class="col-lg-12 form-group mt-2">
                                                    <div class="">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2">
                                                                <label for="inputEmail4" style="text-transform: capitalize;">{{$color->value}}</label>
                                                            </div>
                                                            <div class="n-chk col-md-4">
                                                                <label class="new-control new-checkbox checkbox-primary">
                                                                  <input type="radio" class="new-checkbox-rounded" name="color[{{$color->id}}]" value="light">
                                                                  <span class="new-control-indicator"></span>Light Art Work
                                                                </label>
                                                            </div>
                                                            <div class="n-chk col-md-4">
                                                                <label class="new-control new-checkbox checkbox-primary">
                                                                  <input type="radio" class="new-checkbox-rounded" name="color[{{$color->id}}]" value="dark">
                                                                  <span class="new-control-indicator"></span>Dark Art Work
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            <a href="{{ url('shopProducts/') }}" class="btn btn-danger btn-icon-split">
                                                <span class="icon text-white-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                                    
                                                </span>
                                                <span class="text">Go Back</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
@endsection
@section('scripts')
    <script type="text/javascript" src="{!! asset('js/jquery-3.5.1.js') !!}"></script>
    
    <script type="text/javascript">
    function calctotal(){
        var profit = jQuery('#profit').val() * 1;
        var total = jQuery('#total').attr('title') * 1;
        var newtotal = profit + total;
        jQuery('#total').attr('title',newtotal);
        jQuery('#total').text(newtotal);
    }
    function changeStatus(element,id,name){
        if(jQuery('.'+element).attr('disabled') == 'disabled'){
            jQuery('.'+element).removeAttr('disabled');
            var amount = jQuery('#price_'+id).val() *1;
            var htm = '<span id="span_price_'+id+'">'+name+' : '+amount+'<span>'
            var total = jQuery('#total').attr('title') * 1;
            var newtotal = amount + total;
            jQuery('#total').attr('title',newtotal);
            jQuery('#total').text(newtotal);       
            jQuery('#price_div ').append(htm);
        }else{
            jQuery('.'+element).attr('disabled','disabled');
            jQuery('#span_price_'+id).remove();
            var total = jQuery('#total').attr('title') * 1;
            var amount = jQuery('#price_'+id).val() *1;
            var newtotal = total - amount;
            jQuery('#total').attr('title',newtotal);
            jQuery('#total').text(newtotal);
        }
    }
    $(document).ready(function(){
        
    });
    </script>
@endsection
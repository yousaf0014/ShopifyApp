@extends('layouts.shop.app')
@section('content')

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Prodduct Form Add (Step 2)</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => 'shopProductsStep2/'.$adminProduct->id,'id'=>'add_product','name'=>'add_product','class'=>'form-horizontal','enctype'=>'multipart/form-data')) !!}
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
                                                    <?php $localPrice  = localAmount($adminProduct->price); ?>
                                                    <div class="mt-3">Vendor Price: {{$localPrice['amount'].' ('.$localPrice['currency'].')'}}</div>
                                                    <div class="form-group mb-4 mt-4">
                                                        <label for="formGroupExampleInput">Sku</label>
                                                        <input required type="text" class="form-control input-sm" name="sku" value="{{$adminProduct->sku}}" >
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="formGroupExampleInput">Name</label>
                                                        <input required type="text" class="form-control input-sm" name="name" value="{{$adminProduct->name}}" >
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="formGroupExampleInput">Details</label>
                                                        <textarea required name="details" id="details" class="form-control input-sm">{{$adminProduct->details}}</textarea>
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
                                              <?php foreach($productAttributeGroups as $group){ ?>
                                                    
                                                    <div class="col-lg-12 form-group mb-4">
                                                        <label for="formGroupExampleInput" style="text-transform:capitalize;">{{$group->name}}</label>
                                                        <select required id="group_{{$group->id}}" name="attribute[{{$group->id}}][]" class="chosen-select form-control input-sm" multiple="multiple">
                                                            <option value="">--Slect--</option>
                                                            <?php foreach($group->attribute as $attr){ ?>
                                                                    <option value="{{$attr->id}}">
                                                                        {{$attr->value}}
                                                                    </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                            <?php } ?>
                                            </div>
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
    <link rel="stylesheet" href="{!! asset('chosen/chosen.min.css')!!}">
    <script type="text/javascript" src="{!! asset('js/jquery-3.5.1.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('chosen/chosen.jquery.js') !!}"></script>

    <script type="text/javascript">
    $(document).ready(function(){
        $(".chosen-select").chosen();
    });
    </script>
@endsection
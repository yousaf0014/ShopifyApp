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
                                <h4>Orders/ Import Order</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => 'storeOrders/file','id'=>'add_orders','name'=>'add_orders','enctype'=>'multipart/form-data','class'=>'form-horizontal')) !!}
                            
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Store</label>
                                <select class="form-control form-control-lg" required="" name="store">
                                    <option value="">--Select Store--</option>
                                    <?php foreach($stores as $store){ 
                                            if($store->type == 'store'){
                                            ?>
                                            <option {{$store->id == $selectedStore? 'selected="selected"':''}}value="{{$store->id}}">{{$store->name}}</option>
                                        <?php }
                                        } ?>
                                </select>
                            </div>
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Orders</label>
                                <input type="file" name="orders" value="" class="form-control input-sm" placeholder='fileupload' accept=".csv"  />
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            
                            <a href="{{ url('storeOrders') }}" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                    
                                </span>
                                <span class="text">Go Back</span>
                            </a>

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
    <script type="text/javascript">
    $(document).ready(function(){
    });
    </script>
@endsection
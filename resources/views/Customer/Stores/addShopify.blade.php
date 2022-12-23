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
                                <h4>Add Shopify Store</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => 'addShopifyStore/storeData','id'=>'add_store','name'=>'add_store','class'=>'form-horizontal','method'=>'get')) !!}
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Shop Url</label>
                                <input type="text" name="shop" value="<?php echo !empty($shop)? $shop:''; ?>" class="form-control input-sm" placeholder='URL' />
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Create</button>
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
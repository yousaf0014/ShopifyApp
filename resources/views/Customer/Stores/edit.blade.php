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
                                <h4>Edit Non Shopify Store</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => url('orders/update/'.$user->id),'method'=>'PUT','id'=>'edit_store','name'=>'add_store','class'=>'form-horizontal')) !!}
                             <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Store Name</label>
                                <input type="text" name="name" value="{{$user->name}}" required="" class="form-control input-sm" placeholder='Store name' />
                            </div>
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Email<small>(optional)</small></label>
                                <input type="text" name="email" value="{{$user->email}}" class="form-control input-sm" placeholder='Email' />
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Currency</label>
                                <input type="text" name="currency" value="{{$user->currency}}" required="" class="form-control input-sm" placeholder='Currency Like AUD' />
                            </div>     
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            <a href="{{ url('stores') }}" class="btn btn-danger btn-icon-split">
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/jquery.form.js?v=1')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.validate.min.js?v=1')}}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        options = {
                rules: {
                    "title": {required:true}
                },
                messages: {
                    "name": "Please Title"
                }
            };
            
            $('#edit_category').validate( options );
    });
    </script>
@endsection
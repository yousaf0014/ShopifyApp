@extends('layouts.admin.app')
@section('content')

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Product Edit</h4>
                            </div>
                        </div>
                    </div>

                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => url('adminProducts/update/'.$adminProduct->id),'method'=>'PUT','enctype'=>'multipart/form-data','id'=>'edit_product','name'=>'add_product','class'=>'form-horizontal')) !!}
                            @include('Admin.Products.formhtml')
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
    <link rel="stylesheet" href="{!! asset('chosen/chosen.min.css')!!}">
    <script type="text/javascript" src="{!! asset('js/jquery-3.5.1.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('chosen/chosen.jquery.js') !!}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.form.js?v=1')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.validate.min.js?v=1')}}"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $(".chosen-select").chosen();
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
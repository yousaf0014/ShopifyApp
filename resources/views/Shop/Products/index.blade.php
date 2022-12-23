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
                                <h4 class="mb-4">Products</h4>
                                <form id="searchCompanies" name="searchSs" action="{{url('shopProducts')}}">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-header">                                
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                    <h4>Search Form</h4>
                                                </div>     
                                            </div>
                                        </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <input type="text" value="{{isset($keyword) ? $keyword : ''}}" name="keyword" class="form-control form-control-lg" placeholder="keyword" />
                                                    </div>
                                                </div>
                                                <div class="col-xl-1">
                                                    <a href="javascript:{}" class="btn btn-primary btn-sm" onclick="$('#searchCompanies').submit();">
                                                        <div class="icon-container">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-xl-2">
                                                    <a href="{{url('shopProducts/create/')}}" class="btn btn-success btn-sm">
                                                        <span class="icon text-white-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                                        </span>
                                                        <span class="text">Add Product</span>
                                                    </a>
                                                </div>
                                                <div class="col-xl-1"> 
                                                    <a target="_blank" href="{!! asset('import sample.csv') !!}" class="btn btn-success btn-sm" title="download sample file">
                                                        <span class="icon text-white-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                        </span>
                                                        <span class="text"></span>
                                                    </a>

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="row">
                            <?php
                            $counter = ($products->currentPage()-1) * $products->perPage();
                        ?>
                            @foreach ($products as $row)
                                <?php $counter++; ?>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 widget-content widget-content-area">
                                    <div class="widget widget-chart-two">
                                        <div class="widget-heading">
                                            <h5 class="">{{$row->title}}</h5>
                                        </div>
                                        <div class="widget-content" style="position: relative;">
                                            <?php if(!empty($row->product_pic)){ ?>
                                                <!--<img src="{{Storage::disk('public')->url('uploads/'.$row->product_pic)}}" width="200px"> -->
                                                <img src="{{url('/').'/../storage/app/public/uploads/'.$row->product_pic}}" width="250px">
                                            <?php } ?>
                                            <div class="">Sku: {{$row->sku}}</div>
                                            <div class="">Price: {{$row->price}}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            {!! $products->render() !!}
                        </div>
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
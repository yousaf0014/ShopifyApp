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
                                <h4 class="mb-4">Prodduct Form Add</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="row">
                            <?php $counter = ($adminProducts->currentPage()-1) * $adminProducts->perPage();?>
                            @foreach ($adminProducts as $row)
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
                                            <?php $localPrice = localAmount($row->price);?>
                                            <div class="">Price: {{$localPrice['amount'].' ('.$localPrice['currency'].')'}}</div>
                                            <div class="">
                                                <a class="btn btn-primary" href="{{url('storeProducts/createProduct/'.$row->id)}}">Create</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            {!! $adminProducts->render() !!}
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
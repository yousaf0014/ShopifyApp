@extends('layouts.admin.app')
@section('content')
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Orders</h6>
                            
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12  layout-spacing">
                <form id="searchCompanies" name="searchSs">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">                                
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Search Form</h4>
                                </div>     
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-3">
                                    <div class="form-group">
                                        <input type="text" value="{{isset($keyword) ? $keyword : ''}}" name="keyword" class="form-control form-control-lg" placeholder="keyword" />
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <select class="form-control form-control-lg" name="shop" id="">
                                            <option value="">--Select Shop--</option>
                                            <?php foreach($shops as $thisshop){ ?>
                                                <option <?php echo $thisshop->id == $shop ? 'selected="selected"':''; ?> value="<?php echo $thisshop->id;?>"><?php echo $thisshop->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <select class="form-control form-control-lg" name="shop" id="">
                                            <option value="">--Select Status--</option>
                                            <?php $statusArr = array('approved'=>'approved','in_process'=>'in_process','shipped'=>'shipped','complete'=>'complete'); 
                                            foreach($statusArr as $key=>$state){ ?>
                                                <option <?php echo $state == $status ? 'selected="selected"':''; ?> value="{{$state}}">{{$state}}</option>
                                            <?php } ?>
                                        </select>
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
                                    <a href="{{url('orders/file')}}" class="btn btn-success btn-sm">
                                        <span class="icon text-white-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                        </span>
                                        <span class="text">Upload File</span>
                                    </a>
                                </div>
                                <div class="col-xl-2"> 
                                    <a target="_blank" href="{!! asset('ordersample.csv') !!}" class="btn btn-success btn-sm" title="download sample file">
                                        <span class="icon text-white-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        </span>
                                        <span class="text">Sample File</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="container">
                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                    <form id="general-info" class="section general-info">
                        <div class="info">
                            <h6 class="">List</h6>
                                <div class="row">
                                    <div class="widget-content widget-content-area">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover mb-4">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">#</th>
                                                        <th class="text-center">Order ID</th>
                                                        <th class="text-center">Shopify Order ID</th>
                                                        <th class="text-center">Created</th>
                                                        <th class="text-center">Items</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Partner</th>
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">Payment</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">View</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $counter = ($orders->currentPage()-1) * $orders->perPage();
                                                    ?>
                                                    @foreach ($orders as $row)
                                                        <?php $counter++; ?>
                                                        <tr>
                                                            <td class="text-center">{{$counter}}</td>
                                                            <td><span>{{$row->id}}</span></td>
                                                            <td><span>{{$row->shopify_order_id}}</span></td>
                                                            <td><span>{{date('M d,Y',strtotime($row->order_date))}}</span></td>
                                                            <td><span>{{$row->items}}</span></td>
                                                            <td><span>{{$row->quantity}}</span></td>
                                                            <td><span>{{$row->user->name}}</span></td>
                                                            <td><span>{{$row->country}}</span></td>
                                                            <td><span>{{$row->name}}</span></td>
                                                            <td><span>{{$row->order_total}}</span></td>
                                                            <td><span>{{$row->status}}</span></td>
                                                            <td class="text-center">
                                                                <a href="{{url('/orders/'.$row->id.'/')}}" title="View">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                    </div>
                                                                    
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        {!! $orders->render() !!}
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
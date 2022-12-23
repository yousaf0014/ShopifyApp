@extends('layouts.orderdesk.app')
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
                                <div class="col-xl-7">
                                    <div class="form-group">
                                        <input type="text" value="{{isset($keyword) ? $keyword : ''}}" name="keyword" class="form-control form-control-lg" placeholder="keyword" />
                                    </div>
                                </div>
                                <div class="col-xl-2">
                                    <div class="form-group">
                                        <select class="form-control form-control-lg" name="status" id="">
                                            <option value="">--Select Status--</option>
                                            <?php $statusArr = array('pending'=>'pending','payment'=>'Payment','cancel'=>'Cancel','in_process'=>'in_process','cancel'=>'cancel','shipped'=>'shipped','complete'=>'complete'); 
                                            foreach($statusArr as $thisstat){ ?>
                                                <option <?php echo $thisstat == $status ? 'selected="selected"':''; ?> value="<?php echo $thisstat;?>"><?php echo $thisstat;?></option>
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
                                    <a href="{{url('ouserOrders/file')}}" class="btn btn-success btn-sm">
                                        <span class="icon text-white-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                        </span>
                                        <span class="text">Upload File</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="container">
                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                    <div id="general-info" class="section general-info">
                        <div class="info">
                            <h6 class="">List</h6>
                                <div class="row">
                                    <div class="widget-content widget-content-area">
                                        <div class="col-12 col-md-12">
                                            <a href="javascript:;" onclick="approveAll()">
                                                Approve All Selected
                                            </a>&nbsp;&nbsp;&nbsp;
                                            <a href="javascript:;" onclick="disapproveAll()">
                                                Cancel All Selected
                                            </a>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover mb-4">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">#</th>
                                                        <th class="text-center" width="5%">
                                                            <a href="javascript:;" onclick="$('.checkbox').attr('checked','checked');">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                                                            </a>
                                                            <a href="javascript:;" onclick="$('.checkbox').removeAttr('checked');">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                                                            </a>
                                                        </th>
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
                                                        <th class="text-center">Approve</th>
                                                        <th class="text-center">cancel</th>
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
                                                            <td>
                                                                <?php if($row->status == 'cancel' || $row->status == 'pending'){ ?>
                                                                    <div class="n-chk">
                                                                        <label class="new-control new-checkbox checkbox-primary">
                                                                          <input type="checkbox" class="checkbox new-control-input" value="{{$row->id}}">
                                                                        </label>
                                                                    </div>
                                                                <?php }else{ ?>
                                                                    &nbsp;
                                                                <?php } ?>
                                                            </td>
                                                            <td><span>{{$row->id}}</span></td>
                                                            <td><span>{{$row->shopify_order_id}}</span></td>
                                                            <td><span>{{date('M d,Y',strtotime($row->order_date))}}</span></td>
                                                            <td><span>{{$row->items}}</span></td>
                                                            <td><span>{{$row->quantity}}</span></td>
                                                            <td><span>{{$row->user->name}}</span></td>
                                                            <td><span>{{$row->country}}</span></td>
                                                            <td><span>{{$row->name}}</span></td>
                                                            <td><span>{{$row->order_total}}</span></td>
                                                            <td><span class="status_{{$row->id}}">{{$row->status}}</span></td>
                                                            <td class="text-center">
                                                                <a href="{{url('/ouserOrders/'.$row->id.'/')}}" title="View">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                    </div>                 
                                                                </a>
                                                            </td>
                                                            
                                                            <?php if($row->status == 'cancel' || $row->status == 'pending'){ ?>
                                                            <td>
                                                                <a class="approve_{{$row->id}}" onclick="approve(this,'{{$row->id}}')" href="javascript:;" title="Approve">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        <span>Approve For Payment</span>
                                                                    </div>
                                                                </a>
                                                                <span class="approve_wait_{{$row->id}}" style="display: none;">
                                                                    Wait...
                                                                </span>
                                                                <span class="approve_done_{{$row->id}}" style="display: none;">
                                                                    Approved
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a class="cancel_{{$row->id}}" onclick="cancelOR(this,'{{$row->id}}')" href="javascript:;" title="View">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                                        <span>Cancel Order</span>
                                                                    </div>
                                                                </a>
                                                                <span class="cancel_wait_{{$row->id}}" style="display: none;">
                                                                    Wait...
                                                                </span>
                                                                <span class="cancel_done_{{$row->id}}" style="display: none;">
                                                                    Canceled
                                                                </span>
                                                            </td>
                                                            <?php }else{ ?>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                            <?php } ?>
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
    function approveAll(){
        var ids = [];
        $('.checkbox:checked').each(function() {
            id = this.value;
            $('.approve_'+id).hide();
            $('.approve_wait_'+id).show();
            ids.push(id); 
        });
        var data = {'_token':'<?php echo csrf_token()?>','ids':ids}
        $.ajax({url: '{{url('/ouserOrders/approveAll')}}',type:'post',data:data, success: function(result){
            if(result == 'success'){
                $('.checkbox:checked').each(function() {
                    id = this.value;
                    $('.approve_wait_'+id).hide();
                    $('.approve_done_'+id).show();
                    $('.status_'+id).text('Payment');
                });
                alert('Payment will be automatically deducted from your account.');
            }else{
                $('.checkbox:checked').each(function() {
                    $('.approve_wait_'+id).hide();
                    $('.approve_'+id).show();
                });
                alert('Error in fulfilling request');
            }
        }});
    }
    function disapproveAll(){
        var ids = [];
        $('.checkbox:checked').each(function() {
            id = this.value;
            $('.cancel_'+id).hide();
            $('.cancel_wait_'+id).show();
            ids.push(id); 
        });
        var data = {'_token':'<?php echo csrf_token()?>','ids':ids}
        $.ajax({url: '{{url('/ouserOrders/cancelAll')}}',type:'post',data:data, success: function(result){
            if(result == 'success'){
                $('.checkbox:checked').each(function() {
                    id = this.value;
                    $('.cancel_wait_'+id).hide();
                    $('.cancel_done_'+id).show();
                    $('.status_'+id).text('Canceled');
                });
            }else{
                $('.checkbox:checked').each(function() {
                    $('.cancel_wait_'+id).hide();
                    $('.cancel_'+id).show();
                });
                alert('Error in fulfilling request');   
            }
        }});
    }
    function approve(elem,id){
        $('.approve_'+id).hide();
        $('.approve_wait_'+id).show();
        $.ajax({url: '{{url('/ouserOrders/approve')}}/'+id,type:'post',data:'_token=<?php echo csrf_token()?>', success: function(result){
            if(result == 'success'){
                $('.approve_wait_'+id).hide();
                $('.approve_done_'+id).show();
                $('.status_'+id).text('Payment');
                alert('Payment will be automatically deducted from your account.');
            }else{
                $('.approve_wait_'+id).hide();
                $('.approve_'+id).show();
                alert('Error in fulfilling request');
            }
        }});
    }

    function cancelOR(elem,id){
        $('.cancel_'+id).hide();
        $('.cancel_wait_'+id).show();
        $.ajax({url: '{{url('/ouserOrders/cancel')}}/'+id,type:'post',data:'_token=<?php echo csrf_token()?>', success: function(result){
                if(result == 'success'){
                $('.cancel_wait_'+id).hide();
                $('.cancel_done_'+id).show();
                $('.status_'+id).text('Canceled');
            }else{
                $('.cancel_wait_'+id).hide();
                $('.cancel_'+id).show();
                alert('Error in fulfilling request');   
            }
        }});
    }
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
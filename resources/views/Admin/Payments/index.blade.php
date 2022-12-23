@extends('layouts.admin.app')
@section('content')
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Payment</h6>
                            
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
                                <div class="col-xl-4">
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
                                                        <th class="text-center">Partner Name</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $counter = ($payments->currentPage()-1) * $payments->perPage();
                                                    ?>
                                                    @foreach ($payments as $row)
                                                        <?php $counter++; ?>
                                                        <tr>
                                                            <td class="text-center">{{$counter}}</td>
                                                            <td><span><a href="{{url('orders/'.$row->order_id)}}">{{$row->order_id}}</a></span></td>
                                                            <td><span>{{$userNames[$row->user_id]}}</span></td>
                                                            <td><span>{{$row->amount}} AUD</span></td>
                                                            <td><span>{{date('M d,Y',strtotime($row->created_at))}}</span></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        {!! $payments->render() !!}
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
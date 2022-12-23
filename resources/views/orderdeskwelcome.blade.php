@extends('layouts.orderdesk.app')
@section('content')

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info"><?php $user = Auth::user(); ?>
                        <h6 class=""><p>You are: {{ $user->name }}</p></h6>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                    <div id="general-info" class="section general-info">
                        <div class="info">
                            <h6 class="">Api Settings</h6>
                            <div class="row">
                                <div class="widget-content widget-content-area">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover mb-4">
                                            <tbody>
                                                <tr>
                                                    <td>Recipe ID</td>
                                                    <td>{{$user->recipe_id}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Partner Billing ID</td>
                                                    <td>{{$user->partner_billing_id}}</td>
                                                </tr>
                                        
                                            </tbody>
                                        </table>
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
    
</script>
@endsection
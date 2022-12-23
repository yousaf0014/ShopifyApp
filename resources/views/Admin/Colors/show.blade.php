@extends('layouts.admin.app')
<!-- if there are creation errors, they will show here -->
@section('content')
<style type="text/css">
.boxColor {
  width: 20px;
  height: 20px;
  margin: 5px;
  border: 1px solid rgba(0, 0, 0, .2);
  display: inline-table;
}    
</style>
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Color Group</h6>
                            
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12  layout-spacing">
                <div id="searchCompanies" >
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">                                
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Details</h4>
                                </div>     
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Group Name</label>
                                        <div>
                                            {{$colorGroup->name}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Colors</label>
                                        <div>
                                            <ul>
                                            <?php foreach($colorGroup->attribute as $attr){ ?>
                                                <li>
                                                    {{$attr->name.' | '.$attr->hash}}
                                                    <div class="boxColor" style="background: #{{$attr->hash}}"></div>
                                                </li>
                                            <?php } ?>
                                            </ul>  
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
</div>
@endsection
@section('scripts')
@endsection
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
                        <h6 class="">{{$adminProduct->name}} Details</h6>
                            
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
                                        <label for="formGroupExampleInput">Category</label>
                                        <div>
                                            {{$category->title}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Product Name</label>
                                        <div>
                                            {{$adminProduct->name}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Details</label>
                                        <div>
                                            {!! $adminProduct->details !!}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Supplier Code</label>
                                        <div>
                                            {{$adminProduct->supplier_code}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Sku</label>
                                        <div>
                                            {{$adminProduct->sku}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Price</label>
                                        <div>
                                            {{$adminProduct->price}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Product Pic</label>
                                        <div>
                                            <img width="200px" src="{{url('/').'/../storage/app/public/uploads/'.$adminProduct->product_pic}}" width="200px">  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Design Pic</label>
                                        <div>
                                            <img width="200px" src="{{url('/').'/../storage/app/public/uploads/'.$adminProduct->design_pic}}" width="200px">  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Groups</label>
                                        <div>
                                            <ul>
                                                <?php foreach($adminProduct->productAttributeGroup as $group){?>
                                                        <li>
                                                            <h5>{{$group->name}}</h5>
                                                            <ul>
                                                                <?php foreach($attributes[$group->id] as $attr){?>
                                                                        <li>{{$attr->value}}
                                                                            <?php if(!empty($attr->color_group_value_id)){?>
                                                                                    <div class="boxColor" style="background: #{{colorFun($attr->color_group_value_id)}}"></div>
                                                                            <?php } ?>
                                                                        </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput">Printing Groups</label>
                                        <div>
                                            <ul>
                                                <?php foreach($adminProduct->productPrintingGroup as $group){?>
                                                        <li>{{$group->name}}</li>
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
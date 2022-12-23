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
                                <h4>Color Edit</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => url('colorGroups/update/'.$colorGroup->id),'method'=>'PUT','id'=>'edit_group','name'=>'add_group','class'=>'form-horizontal')) !!}
                            <div class="form-group mb-4">
                                <label for="formGroupExampleInput">Group</label>
                                <input type="text" name="name" value="<?php echo !empty($colorGroup)? $colorGroup->name:''; ?>" class="form-control input-sm" placeholder='Group Name' />
                            </div>
                            <div class="form-group mb-4">
                                <div class="row">
                                    <div class="col-lg-1 col-1 col-sm-1">
                                    </div>
                                    <div class="col-lg-5 col-5 col-sm-5">
                                        Color Hash
                                    </div>
                                    <div class="col-lg-6 col-6 col-sm-6">
                                        Color Name
                                    </div>
                                </div>
                                    <div id="options_data">
                                        <?php $count = 0;
                                        foreach($colorGroup->attribute as $index=>$attr){
                                            $count = $index;
                                        ?>
                                        <div class="mt-4 row" id="row_{{$index}}">
                                            <div class="col-lg-1 col-1 col-sm-1">
                                                <?php if($index != 0){ ?>
                                                    <a href="javascript:;" onclick="$('#row_{{$index}}').remove()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                            <div class="col-lg-5 col-5 col-sm-5">
                                                <input type="text" name="hash[{{$index}}]" class="form-control input-sm" value="{{$attr->hash}}">
                                            </div>
                                            <div class="col-lg-6 col-6 col-sm-6">
                                                <input type="text" name="color[{{$index}}]" class="form-control input-sm" value="{{$attr->name}}">
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-12 col-12 col-sm-12">
                                        <div class="mt-4 mb-4">
                                            <a href="javascript:;" onclick="addOption()" class="btn-primary btn">
                                                <span class="icon text-white-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-plus"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
                                                </span>
                                                Add Option
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <a href="{{ url('colorGroups') }}" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                    </span>
                                    <span class="text">Go Back</span>
                                </a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="hiddenHtml" style="display:none;">
    <div id="row_XXX" class="row mt-4">
        <div class="col-lg-1 col-1 col-sm-1">
            <a href="javascript:;" onclick="$('#row_XXX').remove()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </a>
        </div>
        <div class="col-lg-5 col-5 col-sm-5">
            <input type="text" name="hash[XXX]" class="form-control input-sm">
        </div>
        <div class="col-lg-6 col-6 col-sm-6">
            <input type="text" name="color[XXX]" class="form-control input-sm">
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
    var index = {{$count}} *1;
    function addOption(){
        var HTM = $('#hiddenHtml ').html();
        index = index+1;
        var replacedHTML = HTM.replace(/XXX/gi,index);
        $('#options_data').append(replacedHTML);    
    }
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
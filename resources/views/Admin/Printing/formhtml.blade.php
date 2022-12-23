<div class="form-group mb-4">
    <label for="formGroupExampleInput">Group</label>
    <input type="text" name="name" value="<?php echo !empty($printingGroup)? $printingGroup->name:''; ?>" class="form-control input-sm" placeholder='Group Name' />
</div>

<div class="form-group mb-4">
    <div class="row">
        <div class="col-1 col-lg-1 col-sm-1">
            Sr.no
        </div>
        <div class="col-5 col-lg-5 col-sm-5">
            Attribute
        </div>
        <div class="col-5 col-lg-5 col-sm-5">
            Amount
        </div>
        <div class="col-1 col-lg-1 col-sm-1">
            Action
        </div>
    </div>
    <div class="clearfix"></div>
    <div id="attribute_div">
        <?php  $index = 1;
        if(empty($printingGroup->attribute[0]->id)){?>
                <div class="row" id="div_1">
                    <div class="col-1 col-lg-1 col-sm-1">
                        1
                    </div>
                    <div class="col-5 col-lg-5 col-sm-5">
                        <input type="text" required="" name="attributes[1]" value="" class="form-control input-sm">
                    </div>
                    <div class="col-5 col-lg-5 col-sm-5">
                        <input type="text" required="" name="amounts[1]" value="" class="form-control input-sm">
                    </div>
                    <div class="col-1 col-lg-1 col-sm-1">
                        
                    </div>
                </div>
                <div class="clearfix"></div>
        <?php }else if(!empty($printingGroup->attribute)){ ?>
        <?php foreach($printingGroup->attribute as $attr){ ?>
                <div class="row" id="div_{{$index}}" style="padding-top:10px">
                    <div class="col-1 col-lg-1 col-sm-1">
                        {{$index}}
                    </div>
                    <div class="col-5 col-lg-5 col-sm-5">
                        <input type="text" required="" name="attributes[{{$index}}]" value="{{$attr->name}}" class="form-control input-sm">
                    </div>
                    <div class="col-5 col-lg-5 col-sm-5">
                        <input type="text" required="" name="amounts[{{$index}}]" value="{{$attr->amount}}" class="form-control input-sm">
                    </div>
                    <?php if($index !=1){ ?>
                    <div class="col-1 col-lg-1 col-sm-1">
                        <a href="javascript:;" onclick="$('#div_{{$index}}').remove()">
                            <div class="icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <?php ;$index++; ?>
                </div>
                <div class="clearfix"></div>
        <?php }
        } ?>
    </div>
    <div class="col-lg-8" style="margin: auto;">
        <a class="btn btn-primary add_more" style="margin-top: 25px;"  onclick="addOption()" href="javascript:;" title="Add more"><span class="glyphicon glyphicon-plus"></span>&nbsp;Add Option</a>
    </div>
</div>
<button type="submit"  class="btn btn-primary btn-sm">Save</button>
<a href="{{ url('printingGroups') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a>
<div style="display:none" id="hiddenhtml">
    <div class="row" id="div_XXX" style="padding-top:10px">
        <div class="col-1 col-lg-1 col-sm-1">
            XXX
        </div>
        <div class="col-5 col-lg-5 col-sm-5">
            <input type="text"  name="attributes[XXX]" value="" class="form-control input-sm">
        </div>
        <div class="col-5 col-lg-5 col-sm-5">
            <input type="text"  name="amounts[XXX]" value="" class="form-control input-sm">
        </div>
        <div class="col-1 col-lg-1 col-sm-1">
            <a href="javascript:;" onclick="$('#div_XXX').remove()">
                <div class="icon-container">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </div>
            </a>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<input type="hidden" id="index" value="{{++$index}}">
<script type="text/javascript">
    function addOption(){
        var index = $('#index').val()*1;
        var HTM = $('#hiddenhtml ').html();
        var replacedHTML = HTM.replace(/XXX/gi,index);
      $('#attribute_div').append(replacedHTML);
      $('#div_'+index+' input').attr('required','true');
      index = index+1;
      $('#index').val(index);
    }
</script>
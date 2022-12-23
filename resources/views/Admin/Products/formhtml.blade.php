<style type="text/css">
.boxColor {
  width: 20px;
  height: 20px;
  margin: 5px;
  border: 1px solid rgba(0, 0, 0, .2);
  display: inline-table;
}
</style>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Name</label>
    <input type="text" name="name" required="" value="<?php echo !empty($adminProduct)? $adminProduct->name:''; ?>" class="form-control input-sm" placeholder='Name' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Details</label>
    <textarea name="details" class="form-control input-sm" placeholder='Details' /><?php echo !empty($adminProduct)? $adminProduct->details:''; ?></textarea>
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Sku</label>
    <input type="text" name="sku" required="" value="<?php echo !empty($adminProduct)? $adminProduct->sku:''; ?>" class="form-control input-sm" placeholder='Sku' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Supplier Code</label>
    <input type="text" required="" name="supplier_code" value="<?php echo !empty($adminProduct)? $adminProduct->supplier_code:''; ?>" class="form-control input-sm" placeholder='supplier code' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Product Pic</label>
    <input type="file" required="" name="product_pic" value="<?php echo !empty($adminProduct)? $adminProduct->product_pic:''; ?>" class="form-control input-sm" placeholder='Product Pic' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Design Pic</label>
    <input type="file" name="design_pic" value="<?php echo !empty($adminProduct)? $adminProduct->design_pic:''; ?>" class="form-control input-sm" placeholder='Design Pic' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Price</label>
    <input type="text" required="" name="price" value="<?php echo !empty($adminProduct)? $adminProduct->price:''; ?>" class="form-control input-sm" placeholder='price' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Colors</label>
    <select class="chosen-select form-control input-sm" required="" id="colorsSelect" multiple="" name="colors[]">
        <option value="">--Select Colors--</option>
        <?php foreach($colorGroupsData as $group){ ?>
            <optgroup label="{{$group->name}}">
                <?php foreach($group->attribute as $attr){?>
                        <option style="background-color:#{{$attr->hash}}"
                            <?php echo in_array($attr->id,$selectColors)? 'selected="selected"':'';?>
                            value="{{$attr->id}}">
                             {{$attr->name}}
                        </option>
                <?php } ?>
            </optgroup>
        <?php } ?>
    </select>
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Attribute Groups</label>
    <select class="chosen-select form-control input-sm"  multiple="" required="" name="attributeGroups[]">
        <option value="">--Select Prdocut Attribute Group--</option>
        <?php foreach($groups as $group){ ?>
            <option value="{{$group->id}}" <?php echo !empty($selected[$group->id]) ? 'selected="selected"':'';?> >{{$group->name}}</option>
        <?php } ?>
    </select>
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Printing Groups</label>
    <select class="chosen-select form-control input-sm"  multiple="" required="" name="printingGroups[]">
        <option value="">--Select Prdocut Printing Group--</option>
        <?php foreach($printingGroups as $group){ ?>
            <option value="{{$group->id}}" <?php echo !empty($selectedPrintings[$group->id]) ? 'selected="selected"':'';?> >{{$group->name}}</option>
        <?php } ?>
    </select>
</div>

<button type="submit" class="btn btn-primary btn-sm">Save</button>

<a href="{{ url('adminProducts/'.$category->id) }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
    </span>
    <span class="text">Go Back</span>
</a>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Colors</h5>
                <button type="button" class="close text-white-50" data-dismiss="modal" aria-label="Close">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach($colorGroupsData as $group){ ?>
                    <div class="row">
                        <h3 class="ml-3">
                            <?php echo $group->name;?>
                        </h3>
                        <ul class="row col-lg-12 ml-5">
                            <?php foreach($group->attribute as $attr){?>
                                <li class="col-lg-4 col-4 col-sm-4">
                                    <div class="n-chk">
                                        <label class="new-control new-checkbox">
                                          <input style="background: #{{$attr->hash}}" type="checkbox" onclick="selectedRow(this,'{{$attr->id}}','{{$attr->name}}')" class="new-control-input">
                                          <span class="new-control-indicator"></span>&nbsp;&nbsp;{{$attr->name}}
                                          <div class="boxColor" style="background: #{{$attr->hash}}"></div>
                                        </label>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function selectedRow(elem,id,name){
        //#colorsSelect
    }
</script>
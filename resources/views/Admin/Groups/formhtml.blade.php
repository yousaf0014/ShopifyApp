<div class="form-group mb-4">
    <label for="formGroupExampleInput">Group</label>
    <input type="text" name="name" value="<?php echo !empty($productAttributeGroup)? $productAttributeGroup->name:''; ?>" class="form-control input-sm" placeholder='Group Name' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Attributes <small>(comma seprated)</small></label>
    <textarea name="attributes" class="form-control input-sm" placeholder='XXL,XL,L,M,S' /><?php if(!empty($productAttributeGroup)){
			$seperator = '';
			foreach($productAttributeGroup->attribute as $attr){
				echo $seperator.$attr->value;
				$seperator = ',';
			}
    	} ?></textarea>
</div>
<button type="submit" class="btn btn-primary btn-sm">Save</button>

<a href="{{ url('productAttributeGroups') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a>
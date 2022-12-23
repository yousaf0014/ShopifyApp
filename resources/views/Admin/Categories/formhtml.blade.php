<div class="form-group mb-4">
    <label for="formGroupExampleInput">Title</label>
    <input type="text" name="title" value="<?php echo !empty($category)? $category->title:''; ?>" class="form-control input-sm" placeholder='Title' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Details</label>
    <textarea name="details" class="form-control input-sm" placeholder='Details' /><?php echo !empty($category)? $category->details:''; ?></textarea>
</div>
<button type="submit" class="btn btn-primary btn-sm">Save</button>

<a href="{{ url('categories') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a>
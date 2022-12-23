<div class="form-group mb-4">
    <label for="formGroupExampleInput">Country</label>
    <input type="text" readonly="tre" value="<?php echo $countryCode->country;?>" class="form-control input-sm" placeholder='Shipment Charges' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Alpha-2 Code</label>
    <input type="text" readonly="true" value="<?php echo $countryCode->code2;?>" class="form-control input-sm" />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Alpha-2 Code</label>
    <input type="text" value="<?php echo $countryCode->code3;?>" class="form-control input-sm" placeholder='Shipment Charges' />
</div>

<div class="form-group mb-4">
    <label for="formGroupExampleInput">Shipment Charges</label>
    <input type="text" name="shipment_charges" value="<?php echo $countryCode->shipment_charges;?>" class="form-control input-sm" placeholder='Shipment Charges' />
</div>
<div class="form-group mb-4">
    <label for="formGroupExampleInput">Additional/Item</label>
    <input type="text" name="additional_charge" value="<?php echo $countryCode->additional_charge; ?>" class="form-control input-sm" placeholder='Additional/Item' />
</div>
<button type="submit" class="btn btn-primary btn-sm">Save</button>

<a href="{{ url('shipment') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
    </span>
    <span class="text">Go Back</span>
</a>
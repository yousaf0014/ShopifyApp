<div class="row">
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing ">
                <ul>
                    <li><a href="{{url('shopProducts/create/')}}" class="<?php echo  empty($category)? 'selected':'';?>">All</a></li>
                    <?php foreach($categories as $cat){ ?>
                            <li><a href="{{url('shopProducts/create/'.$cat->id)}}">{{$cat->title}}</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9 col-9">
        <div class="container">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <?php
                        $counter = ($adminProducts->currentPage()-1) * $adminProducts->perPage();
                    ?>
                    @foreach ($adminProducts as $row)
                        <?php $counter++; ?>
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-6 widget-content widget-content-area">
                        <div class="widget widget-chart-two">
                            <div class="widget-heading">
                                <h5 class="">{{$row->title}}</h5>
                            </div>
                            <div class="widget-content" style="position: relative;">
                                <?php if(!empty($row->product_pic)){ ?>
                                    <!--<img src="{{Storage::disk('public')->url('uploads/'.$row->product_pic)}}" width="200px"> -->
                                    <img src="{{url('/').'/../storage/app/public/uploads/'.$row->product_pic}}" width="250px">
                                <?php } ?>
                                <div class="">Sku: {{$row->sku}}</div>
                                <div class="">Price: {{$row->price}}</div>
                                <div class="">
                                    <a class="btn btn-primary" href="{{url('shopProducts/createProduct/'.$row->id)}}">Create</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {!! $adminProducts->render() !!}
            </div>
        </div>
    </div>
</div>
<button type="submit" class="btn btn-primary btn-sm">Save</button>

<a href="{{ url('shopProducts/') }}" class="btn btn-danger btn-icon-split">
    <span class="icon text-white-50">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
        
    </span>
    <span class="text">Go Back</span>
</a>

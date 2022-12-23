@extends($layout)
@section('content')
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Notifications</h6>
                            
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                    <div id="general-info" class="section general-info">
                        <div class="info">
                            <h6 class="">List</h6>
                                <div class="row">
                                    <div class="widget-content widget-content-area">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover mb-4">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" width="5%">#</th>
                                                        <th class="text-center">Shop Name</th>
                                                        <th class="text-center">Title</th>
                                                        <th class="text-center">Details</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Mark Read</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $counter = ($notifications->currentPage()-1) * $notifications->perPage();
                                                    ?>
                                                    @foreach ($notifications as $row)
                                                        <?php $counter++; ?>
                                                        <tr>
                                                            <td class="text-center">{{$counter}}</td>
                                                            <td class="text-center">{{$userNames[$row->user_id]}}</td>
                                                            <td class="text-center"><span>{{$row->title}}</span></td>
                                                            <td class="text-center"><span>{!! $row->details !!}</span></td>
                                                            <td class="text-center"><span>{{date('M d,Y',strtotime($row->created_at))}}</span></td>
                                                            <td class="text-center">
                                                                <a href="{{url('/notifications/delete/'.$row->id.'/')}}" title="View">
                                                                    <div class="icon-container">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                                        <span>Mark Read</span>
                                                                    </div>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        {!! $notifications->render() !!}
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
    $(document).ready(function(){
        <?php if(!empty($_GET['id'])){
            //window.location.href = "{{url('/notifications/delete/'.$_GET['id'])}}";
        } ?>
        $.ajaxSetup({
           headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });

        $("#myModal").on("show.bs.modal", function(e) {
            url =  $(e.relatedTarget).data('target-url');
            $.get( url , function( data ) {
                $(".modal-body").html(data);
            });

        });
    });
    function show_alert(id) {
        if(confirm('Are you sure? you want to delete.')){
            $('#delete_'+id).submit();
        }else{
            return false;
        }
    }
</script>
@endsection
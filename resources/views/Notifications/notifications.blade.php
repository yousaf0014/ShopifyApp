<?php foreach($notifications as $nt){?>
<a class="dropdown-item" href="{{url('notifications').'?id='.$nt->id}}">
    <div class="">
        <div class="media notification-new">
            <div class="notification-icon">
                <div class="icon-svg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
            </div>
            <div class="media-body">

                    <p class="meta-title mr-3">{{$nt->title}}</p>
                    <p class="meta-time align-self-center mb-0">{{date('M,d Y',strtotime($nt->created_at))}}</p>
            </div>
        </div>
    </div>
</a>
<?php } ?>

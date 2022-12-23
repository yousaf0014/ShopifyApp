<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notification;

class NotificationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $stores;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authUser = Auth::user()->id;
            $this->stores = \App\User::where('parent_id',$authUser)->get();
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $userArr[] = $user->id;
        $userNames[$user->id] = $user->name;
        foreach($this->stores as $st){
            $userArr[] = $st->id;
            $userNames[$st->id] = $st->name; 
        }
        $layout = $user->type == 'shop' ? 'layouts.shop.app':'layouts.customer.app';
        $notifications = Notification::whereIn('user_id',$userArr)->paginate(20);
        return view('Notifications.index',compact('notifications','layout','userNames'));
    }

    public function delete(Request $request,Notification $notification){
        $user = Auth::user();
        $userArr[$user->id] = $user->id;
        foreach($this->stores as $st){
            $userArr[$st->id] = $st->id;
        }
        if(!empty($userArr[$notification->user_id])){
            $notification->delete();
            flash('Successfully Marked.','success');
            return back();
        }
        flash('You do not have permissions.','error');
        return back();
    }
    
    public function getNotifications(Request $request){
        $user = Auth::user();
        $userArr[] = $user->id;
        foreach($this->stores as $st){
            $userArr[] = $st->id;
        }
        $notifications = Notification::whereIn('user_id',$userArr)->pageinate(5);
        $count = count($notifications);
        return view('Notifications.notifications',compact('count','notifications'));
    }

    public function getCount(Request $request){
        $user = Auth::user();
        $userArr[] = $user->id;
        foreach($this->stores as $st){
            $userArr[] = $st->id;
        }
        $notifications = Notification::whereIn('user_id',$userArr)->count();
        return $notifications;
    }
}

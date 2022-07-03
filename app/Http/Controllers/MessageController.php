<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessagesDelivered;
use App\Models\User;
use Cache;
use Carbon\Carbon;
class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
      ///  dd($messages);
      $users = User::select("*")
                        ->whereNotNull('last_seen')
                        ->orderBy('last_seen', 'DESC')
                        ->paginate(10);

                       
                     
        return view('message.index',compact('messages' , 'users'));
    }

    public function store(Request $request)
    {
     
       /* 
     //   dd(json_encode ($request->all()));

//$messages = auth()->user()->messages()->create($request->all());
        $arr = $request->body ;
      //  dd( $arr );

        dd(json_encode (gettype($arr)));
     
       */
     $messages = auth()->user()->messages()->create($request->all());


     broadcast(new MessagesDelivered($messages->load('users')))->toOthers();
  

    }
}

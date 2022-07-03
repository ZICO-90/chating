@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h1>chat</h1>
            <h3>Online Users</h3>
            <h3 id="no-onlien-users"> no online Users</h3>
            <ul class="list-group" id="online-users">
          
            </ul>
        </div>


       <div class="col-md-7"  sytle="height: 200px;">
            <h3>Chat</h3>
            <div id="chat" class="h-100 bg-white mb-4 p-5"  style="overflow-y:scroll;"> 
                 @foreach($messages as $textChat) 
            <div class="mt-2 w-50 text-black p-3 rounded {{ auth()->user()->id == $textChat->user_id ? 'bg-primary' : 'bg-warning' }}" style ="float:{{ auth()->user()->id == $textChat->user_id ? 'right' : 'left' }};">
            <p>name:{{$textChat-> users->name}}  </p>
            <p>{{$textChat-> body}} </p>
            </div>
            <div style="clear:both"></div>
            @endforeach
            </div>
        
            <form class="d-flex" >
                <input type="text" name="" id="chat-text" data-url="{{ route('message.store') }}" style="margin-right: 10px" class="form-control">
                <button class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
    </div>
</div>

<div class="container">
    <h1>Laravel Display Online Users - ItSolutionStuff.com</h1>
  
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Last Seen</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                    </td>
                    <td>
                        @if(Cache::has('user-is-online-' . $user->id))
                            <span class="text-success">Online</span>
                        @else
                            <span class="text-secondary">Offline</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
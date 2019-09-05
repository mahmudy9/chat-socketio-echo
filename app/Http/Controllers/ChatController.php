<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Chat;
use App\Message;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatEvent;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->except(['testSocketio']);
    }

    public function ChatPage()
    {
        $users = User::where('id' , '<>' , Auth::id())->pluck('id' , 'name')->toArray();
        return response()->json(['data' => $users]);
    }

    public function startChat($userid)
    {
        if(!User::where( 'id' ,$userid)->exists())
        {
            return response()->json(['error' => 'error user not found']);
        }
        if(Chat::where('user1_id' , Auth::id())->where('user2_id' , $userid)->exists()) {
            $chatid = Chat::where('user1_id', Auth::id())->where('user2_id', $userid)->value('id');
            return response()->json(['chatid' => $chatid ]);
        } 
        if(Chat::where('user1_id', $userid)->where('user2_id', Auth::id())->exists()){
            $chatid = Chat::where('user1_id', $userid)->where('user2_id', Auth::id())->value('id');
            return response()->json(['chatid' => $chatid]);
        }
        $chat = new Chat;
        $chat->user1_id = Auth::id();
        $chat->user2_id = $userid;
        $chat->save();
        return response()->json(['chatid' => $chat->id]);
    }

    public function getChat($chatid)
    {
        if(!Chat::where( 'id' ,$chatid)->exists())
        {
            return response()->json(['error' => 'no such chat exists']);
        }
        if(!Chat::where('id' , $chatid)->where( 'user1_id' ,Auth::id())->exists() && !Chat::where('id', $chatid)->where('user2_id' , Auth::id())->exists() ){
            return response()->json(['error' => 'unauthorized']);
        }
        $chat = Message::where('chat_id' , $chatid)->get();
        return response()->json(['data' => $chat]);
    }

    public function postMessage(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'message' => 'required|min:1|max:1000',
            'chat_id' => 'required|integer|exists:chats,id'
        ]);
        if($validator->fails()){
            return response()->json(['error' =>$validator->errors() ]);
        }
        $reqmessage = $request->input('message');
        $chatid = $request->input('chat_id');

        if (!Chat::where('id', $chatid)->where('user1_id', Auth::id())->exists() && !Chat::where('id', $chatid)->where('user2_id', Auth::id())->exists()) {
            return response()->json(['error' => 'unauthorized']);
        }

        if(Chat::where('id' , $chatid)->where('user1_id' , Auth::id())->exists())
        {
            $to_id = Chat::where('id' , $chatid)->value('user2_id');
        }
        if(Chat::where('id' , $chatid)->where('user2_id' , Auth::id())->exists()){
            $to_id = Chat::where('id', $chatid)->value('user1_id');
        }

        $message = new Message;
        $message->from_id = Auth::id();
        $message->to_id = $to_id;
        $message->chat_id = $chatid;
        $message->fromusername = Auth::user()->name;
        $message->tousername = User::find($to_id)->name;
        $message->message = $reqmessage;
        $message->save();
        return response()->json(['data' => $message]);
    }

    public function testSocketio()
    {
        return view('home');
    }

    public function sendevent(Request $request)
    {
        event(new ChatEvent($request->input('message')));
    }
    
}

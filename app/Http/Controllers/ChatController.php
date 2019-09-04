<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Chat;
use App\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
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
        $messages = 
    }
}

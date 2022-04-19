<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class FavoritesController extends Controller
{
//  public function index()
//     {
//         $user = \Auth::user();
//         return view('favorites.index'
//         , [
//              'user' => $user,
//             //  'microposts' => $microposts,
//             ]
//             );
//     }
    
    
    public function store($micropost_id)
    {
        // dd($micropost_id);
        // dd($micropost_id);
        \Auth::user()->favorite($micropost_id);
        // 前のURLへリダイレクトさせる
        return back();
    }
    
    
    
    public function destroy($micropost_id)
    {
        \Auth::user()->un_favorite($micropost_id);
        return back();
    }
 

    
    public function favorite_ings($id)
    {
        $user =  User::findOrFail($id);
                // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();
        // dd($user);
        $favorites = $user->feed_favorites()->orderBy('created_at', 'desc')->paginate(10);
        return view('users.favorite_ings',[
            'favorites' => $favorites,
            'user' => $user,]);
    }
    
        public function favorite_mine()
    {   
        $user = \Auth::user();
        $user->loadRelationshipCounts();
        $favorites = $user->feed_favorites()->orderBy('created_at', 'desc')->paginate(10);
        return view('users.favorite_ings',[
            'favorites' => $favorites,
            'user' => $user,]);
    }
    

    




}
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
        $user=  User::findOrFail($id);
        $favorites = $user->feed_favorites()->orderBy('created_at', 'desc')->paginate(10);
        return view('users.favorite_ings',[
            'favorites' => $favorites,
            'user' => $user,]);
    }
    
    
    public function show($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザの投稿一覧を作成日時の降順で取得
        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);

        // ユーザ詳細ビューでそれらを表示
        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }




}
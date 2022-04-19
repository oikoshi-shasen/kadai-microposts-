@if (Auth::user()->is_favorite_ing($micropost->id))
    {!! Form::open(['route' => ['micropost.unfavorites'],'url' => 'unfavorites/'.$micropost->id,'method'=>'delete'] ) !!}
        {!! Form::submit('UnFavorite', ['class' => "btn btn-warning btn-sm"]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => ['micropost.favorites'],'url' => 'favorites/'.$micropost->id,'method'=>'POST'] ) !!}
        {!! Form::submit('Favorite', ['class' => "btn btn-success btn-sm"]) !!}
    {!! Form::close() !!}
@endif

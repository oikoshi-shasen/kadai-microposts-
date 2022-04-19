@if (count($favorites) > 0)
    <ul class="list-unstyled">
        @foreach ($favorites as $favorite)
           
        @endforeach
    </ul>
        {{-- ページネーションのリンク --}}

@endif
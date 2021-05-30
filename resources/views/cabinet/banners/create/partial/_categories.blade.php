<ul>
    @foreach($categories as $category)
        <li>
            <a href="{{ route('cabinet.banners.region', $category) }}">{{ $category->name }}</a>
            @include('cabinet.banners.create.partial._categories', ['categories' => $category->children])
        </li>
    @endforeach
</ul>
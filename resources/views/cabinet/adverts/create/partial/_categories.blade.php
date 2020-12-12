<ul>
    @foreach($categories as $category)
        <li>
            <a href="{{ route('cabinet.advert.region', $category) }}">{{ $category->name }}</a>
            @include('cabinet.adverts.create.partial._categories', ['categories' => $category->children])
        </li>
    @endforeach
</ul>
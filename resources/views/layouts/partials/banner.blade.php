<a href="{{ route('banner.click', $banner) }}">
    <img src="{{ $banner->getSrc() }}" alt="{{ $banner->name }}" width="{{ $banner->getWidth() }}" height="{{ $banner->getHeight() }}">
</a>
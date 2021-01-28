<div class="row search-wrap">
    <div class="container">
        <div class="row">
            <div class="col-9">
                <form action="{{ route('adverts.index') }}" method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="{{ __('Enter text ad...') }}" aria-label="{{ __('Enter text ad...') }}" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-3">
                <a href="{{ route('cabinet.advert.category') }}" class="btn btn-success float-right">
                    <i class="fa fa-plus"></i>
                    {{ __('Create ads') }}
                </a>
            </div>
        </div>
    </div>
</div>
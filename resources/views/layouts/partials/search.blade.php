<div class="row search-wrap">
    <div class="container">
        <div class="row">
            <div class="col-9">
                <form action="{{ $action }}" method="get">
                    <div class="input-group mb-3">
                        <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="{{ __('Enter text ad...') }}" aria-label="{{ __('Enter text ad...') }}" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>

                    @if($category)
                        <div class="row">
                            @foreach($category->getAllAttributes() as $attribute)
                                @if($attribute->isSelect() || $attribute->isNumber())
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">{{ $attribute->name }}</label>
                                            @if($attribute->isSelect())
                                                <select name="attrs[{{ $attribute->id }}][equals]" id="" class="form-control">
                                                    <option value=""></option>
                                                    @foreach($attribute->variants as $variant)
                                                        <option value="{{ $variant }}"{{ request()->input('attrs.' . $attribute->id . '.equals') ? ' selected' : '' }}>{{ $variant }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($attribute->isNumber())
                                                <div class="row">
                                                    <div class="col-6">
                                                        <input
                                                                type="number"
                                                                name="attrs[{{ $attribute->id }}][from]"
                                                                value="{{ request()->input('attrs.' . $attribute->id . '.from') }}"
                                                                class="form-control"
                                                                placeholder="{{ __('From') }}"
                                                        >
                                                    </div>
                                                    <div class="col-6">
                                                        <input
                                                                type="number"
                                                                name="attrs[{{ $attribute->id }}][to]"
                                                                value="{{ request()->input('attrs.' . $attribute->id . '.to') }}"
                                                                class="form-control"
                                                                placeholder="{{ __('To') }}"
                                                        >
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
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
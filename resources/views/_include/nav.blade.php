    <div class="col-md-12 col-sm-12 col-xs-12 nav">
        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1 menu">
            <h1 class="title"><a href="{{ asset('/') }}">Zalora Crawler</a></h1>
            <!-- <span><i class="fa fa-plus-circle fa-2x"></i></span> -->
            <div class="clear"></div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 sub-nav">
            <div class="col-md-offset-1">
                <ul>
                <a href="{{ route('category-all') }}"><li class="sub-title">Semua</li></a>
                @foreach(App\Product::getCachedCategory() as $key => $category)
                    @if(strtolower($key) != "style")
                    <li class="sub-title">{{ $key }} <!-- ({{ $category->count() }}) --></li>
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    </div>

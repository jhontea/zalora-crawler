    <?php
        if (!isset($category)) {
            $category = "";
        }
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12 nav">
        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1 menu">
            <h1 class="title"><a href="{{ asset('/') }}">Zalora Crawler</a></h1>
            <!-- <span><i class="fa fa-plus-circle fa-2x"></i></span> -->
            <div class="clear"></div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 sub-nav">
            <div class="col-md-offset-1">
                <ul>
                <li class="sub-title"><a class="sub-nav-links @if($category == 'all') active @endif" href="{{ route('category', 'all') }}">Semua</a></li>
                @foreach(App\Product::getCachedCategory() as $key => $categories)
                    @if(strtolower($key) != "style")
                        <li class="sub-title"><a class="sub-nav-links @if($category == $key) active @endif"  href="{{ route('category', $key) }}">{{ $key }}</a> <!-- ({{ $categories->count() }}) --></li>
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    </div>

    <?php
        if (!isset($category)) {
            $category = "";
        }
    ?>
    <div class="col-md-12 col-sm-12 col-xs-12 nav">
        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1 menu">

            <h1 class="title"><span class="nav-button-mobile"><i class="nav-icon fa fa-bars"></i></span><a href="{{ asset('/') }}">Zalora Crawler</a></h1>
            <!-- <span><i class="fa fa-plus-circle fa-2x"></i></span> -->
            <div class="nav-search">
            {!! Form::open(['route' => 'search']) !!}
                <div class="input-group">
                    <input type="text" class="form-control" name="searchWord" placeholder="Search">
                    <span class="input-group-btn">
                        <button class="btn btn-black" type="submit"><span class="glyphicon glyphicon-search white"></span></button>
                    </span>
                </div>
            {!! Form::close() !!}
            </div>
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
    <div id="mySidenav" class="sidenav">
        <a href="{{ route('category', 'all') }}">Semua</a>
        @foreach(App\Product::getCachedCategory() as $key => $categories)
        <a href="{{ route('category', $key) }}">{{ $key }}</a>
        @endforeach
        <hr>
    </div>
    <div id="nav-overlay""></div>

@section('page-script')
<script>
    $('.nav-icon').click(function () {
        $('.sidenav').toggleClass('open');
        $('#nav-overlay').toggleClass('open');
        if ($(this).hasClass('fa-bars')) {
            $(this).removeClass('fa-bars');
            $(this).addClass('fa-close');
        } else {
            $(this).removeClass('fa-close');
            $(this).addClass('fa-bars');
        }
    });
    $('#nav-overlay').click(function () {
        $('.sidenav').removeClass('open');
        $('#nav-overlay').removeClass('open');
        $('.nav-icon').removeClass('fa-close');
        $('.nav-icon').addClass('fa-bars');
    });
</script>
@endsection


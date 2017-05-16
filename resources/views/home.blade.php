<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    @include('_include.head')
    <body>
    <div class="col-md-12 nav">
        <div class="col-md-10 col-md-offset-1 main-menu">
            <h1>Zalora Crawler</h1>
            <span><i class="fa fa-plus-circle fa-2x"></i></span>

            <div class="clear"></div>
        </div>
    </div>

    <div class="sub-nav">
    </div>

    <div class="col-md-10 col-md-offset-1">
        <div class="text-center">
            {!! Form::open(['url' => 'crawl']) !!}
            {!! Form::text('url', '', ['placeholder' => 'http:// or https://']) !!}
            {!! Form::submit('Submit') !!}
            {!! Form::close() !!}

            @if(isset($data))
                @if(empty($data))
                    Show error from crawling data
                    @if(session()->has('errorURL'))
                    <p>{{ session()->pull('errorURL') }}</p>
                    @elseif(session()->has('errorNode'))
                    <p>{{ session()->pull('errorNode') }}</p>
                    @endif
                @else
                    Show crawling data
                    @include('crawl')
                @endif
            @endif
        </div>


        @foreach($products as $product)
            <div class="col-md-3 item">
                <img src="{{ $product->image_link }}" class="img-responsive"/>
                <h2>{{ $product->brand }}</h2>
                <div class="product-title">
                    <p>{{ $product->title }}</p>
                </div>

                <p><em>Rp {{ $product->price }}</em>
                </p>
                <div class="text-center">
                    <a href="{{ $product->url }}" target="_blank">
                        <button class="view-zalora" type="button">View on Zalora</button>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    </body>
</html>

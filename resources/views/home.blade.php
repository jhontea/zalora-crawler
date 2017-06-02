<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    @include('_include.header')
    <body>
    <div class="col-md-12 col-sm-12 col-xs-12 nav">
        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1 menu">
            <h1>Zalora Crawler</h1>
            <!-- <span><i class="fa fa-plus-circle fa-2x"></i></span> -->
            <div class="clear"></div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 menu">
            <div class="sub-nav">
                <div class="col-md-offset-1">
                    Sub-Nav
                </div>
            </div>
        </div>
    </div>
    <div class="body">
        <div class="col-md-10 col-md-offset-1">
            <div class="text-center">
                {!! Form::open(['url' => 'crawl']) !!}
                {!! Form::text('url', '', ['placeholder' => 'http:// or https://']) !!}
                {!! Form::submit('Submit') !!}
                {!! Form::close() !!}

                <div class="message">
                @if(isset($data))
                    @if(empty($data))
                        @if(session()->has('errorURL'))
                        <div class="alert alert-danger alert-dismissable fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session()->pull('errorURL') }}
                        </div>
                        @elseif(session()->has('errorNode'))
                        <div class="alert alert-danger alert-dismissable fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ session()->pull('errorNode') }}
                        </div>
                        @endif
                    @else
                        @include('crawl')
                    @endif
                @endif
                </div>
            </div>


            @foreach($products as $product)
                <div class="col-md-3 item">
                    <img src="{{ $product->image_link }}" class="img-responsive"/>
                    <h2>{{ $product->brand }}</h2>
                    <div class="product-title">
                        <p>{{ $product->title }}</p>
                    </div>

                    <div class="price">
                        <p class="@if($product->price_discount) price-striketrough @endif">
                            <em>Rp {{ $product->price }}</em>
                        </p>
                        <p class="discount-price">
                            <em>{{ $product->price_discount? 'Rp '.$product->price_discount : ' ' }}</em>
                        </p>
                    </div>

                    <div class="text-center">
                        <a href="{{ $product->url }}" target="_blank">
                            <button class="zalora-button" type="button">View on Zalora</button>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </body>
</html>

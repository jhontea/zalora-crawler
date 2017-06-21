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
                <div class="col-md-3 col-sm-6 item">
                    <img src="{{ $product->image_link }}" class="img-responsive"/>
                    <h2>{{ $product->brand }}</h2>
                    <div class="product-title">
                        <p>{{ $product->title }}</p>
                    </div>

                    @if($product->priceNow)
                        <div class="price-status">
                            @if($product->priceNow->status == 'lower')
                                <i class="fa fa-arrow-down fa-price-lower fa-2x"></i>
                            @elseif($product->priceNow->status == 'higher')
                                <i class="fa fa-arrow-up fa-price-higher fa-2x"></i>
                            @else
                                <i class="fa fa-window-minimize fa-price-flat fa-2x"></i>
                            @endif
                        </div>
                    @endif

                    <div class="price">
                    @if($product->priceNow)
                        <p class="@if($product->priceNow['price_discount']) price-striketrough @endif">
                            <em>Rp {{ number_format((float)$product->priceNow['price'], 0, '', '.') }} </em>
                        </p>
                        <p class="discount-price">
                            <em>{{ $product->priceNow['price_discount']? 'Rp '.number_format((float)$product->priceNow['price_discount'], 0, '', '.') : ' ' }}</em>
                        </p>
                    @endif
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

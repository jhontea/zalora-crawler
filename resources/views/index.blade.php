@extends('layout.base')

@section('page-content')
    <div class="body">
        <div class="all-product">
            <!-- <div class="text-center">
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
            </div> -->

            <div class="text-center">{{ $products->links() }}</div>

            @foreach($products as $product)
                <div class="col-md-3 col-sm-6 item">
                    <div id="overlay">
                        <a href="{{ asset('detail/'.$product->sku) }}">
                            <div class="view-detail">
                                View detail
                            </div>
                            <img src="{{ $product->image_link }}" class="img-responsive"/>
                        </a>
                    </div>
                    <h2>{{ $product->brand }}</h2>
                    <div class="product-title">
                        <p>{{ $product->title }}</p>
                    </div>

                    @if(!$product->is_active)
                        <div class="price-status">
                            <i class="fa fa-close fa-price-higher fa-2x"></i>
                        </div>
                    @elseif($product->priceNow)
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
                        <p class="@if($product->priceNow['price_discount'] != $product->priceNow['price']) price-striketrough @endif">
                            <em>Rp {{ number_format((float)$product->priceNow['price'], 0, '', '.') }} </em>
                        </p>
                        <p class="discount-price">
                            <em>{{ ($product->priceNow['price_discount'] != $product->priceNow['price'])? 'Rp '.number_format((float)$product->priceNow['price_discount'], 0, '', '.') : ' ' }}</em>
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
            <div class="text-center">{{ $products->links() }}</div>
        </div>
    </div>
@endsection

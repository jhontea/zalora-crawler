@extends('layout.base')

@section('page-content')
    <div class="body">
        <div class="all-product">
            <div class="text-center">
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
                @if($pageError)
                <div class="alert alert-danger alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    Error on page:
                    @foreach($pageError as $error)
                    {{ $error.", " }}
                    @endforeach
                </div>
                @endif
                <div class="alert alert-info alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ count($products) }} barang ditemukan
                </div>
            </div>

            @foreach($products as $product)
                <div class="col-md-3 col-sm-6 item">
                    <div id="overlay">
                        <img src="{{ $product['imageLink'] }}" class="img-responsive"/>
                    </div>
                    <h2>{{ $product['brand'] }}</h2>
                    <div class="product-title">
                        <p>{{ $product['name'] }}</p>
                    </div>

                    <div class="price">
                        <p class="@if($product['priceDiscount'] != $product['price']) price-striketrough @endif">
                            <em>Rp {{ number_format((float)$product['price'], 0, '', '.') }} </em>
                        </p>
                        <p class="discount-price">
                            <em>{{ ($product['priceDiscount'] != $product['price'])? 'Rp '.number_format((float)$product['priceDiscount'], 0, '', '.') : ' ' }}</em>
                        </p>
                    </div>

                    <div class="text-center">
                        <a href="{{ $product['url'] }}" target="_blank">
                            <button class="zalora-button" type="button">View on Zalora</button>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

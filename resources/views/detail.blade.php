@extends('layout.base')

@section('page-content')
<div class="body" id="product-section">
    <div class="col-md-10 col-md-offset-1">
        <div class="col-md-5">
            <img src="{{ $product->image_link }}" class="img-responsive"/>
        </div>
        <div class="col-md-7">
            <div class="item-detail">
                <div class="col-md-12">
                    <h1 class="item-brand">{{ $product->brand }}</h1>
                </div>
                <div class="col-md-12">
                    <h3 class="item-title">{{ $product->title }}</h3>
                </div>
                <div class="col-md-12">
                    @if($product->priceNow)
                        <p class="@if($product->priceNow['price_discount'] != $product->priceNow['price']) price-striketrough @endif">
                            <em>Rp {{ number_format((float)$product->priceNow['price'], 0, '', '.') }} </em>
                        </p>
                        <p class="discount-price">
                            <em>{{ ($product->priceNow['price_discount'] != $product->priceNow['price'])? 'Rp '.number_format((float)$product->priceNow['price_discount'], 0, '', '.') : ' ' }}</em>
                        </p>
                    @endif
                </div>
            </div>
            <div class="item-chart">
                <div class="col-md-12">
                    @if($product->priceChanges->count())
                    <canvas id="myChart"></canvas>
                    @else
                    <p class="alert alert-danger text-center">There is no price change for this product</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="text-center">
                <a href="{{ $product->url }}" target="_blank">
                    <button class="zalora-button" type="button">View on Zalora</button>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@if($product->priceChanges->count())
<script src="{{ asset('assets/js/vendor/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/Chart.min.js') }}"></script>


<script>
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            "{{ strftime('%e %b %Y', strtotime($product->created_at)) }}",
            @foreach($priceChanges as $priceChange)
            "{{ strftime('%e %b %Y', strtotime($priceChange->created_at)) }}",
            @endforeach
        ],
        datasets: [{
            label: '# Price',
            data: [{
                y: '{{ $product->price }}'
            },
            @foreach($priceChanges as $priceChange)
            {
                y: '{{ $priceChange->price }}'
            },
            @endforeach
            ],
            backgroundColor: '#3498db',
            borderColor: '#3498db',
            fill: false,
        }, {
            label: '# Price discount',
            data: [{
                y: '{{ $product->price_discount }}'
            },
            @foreach($priceChanges as $priceChange)
            {
                y: '{{ $priceChange->price_discount }}'
            },
            @endforeach
            ],
            backgroundColor: '#e74c3c',
            borderColor: '#e74c3c',
            fill: false,
        }]
    },
    options: {
        responsive: true,
        elements: {
            line: {
                tension: 0, // disables bezier curves
            }
        },
        title:{
            display:true,
            text:'Price Change'
        },
        scales: {
            xAxes: [{
                display: true,
            }],
            yAxes: [{
                display: true,
            }]
        }
    }
});
</script>
@endif
@endsection

                            <img src="{!! $data['image_link'] !!}">
                            @if($data['exist'])
                            <center>
                                <p>Data already exist</p>
                                <a href="{{ asset('/') }}"><button>Home</button></a>
                            </center>
                            @else
                            {!! Form::open(['route' => 'create']) !!}
                            {!! Form::hidden('title', $data['title']) !!}
                            {!! Form::hidden('brand', $data['brand']) !!}
                            {!! Form::hidden('sku', $data['sku']) !!}
                            {!! Form::hidden('url', $data['url']) !!}
                            {!! Form::hidden('price', $data['price']) !!}
                            {!! Form::hidden('price_discount', $data['priceDiscount']) !!}
                            {!! Form::hidden('category', $data['category']) !!}
                            {!! Form::hidden('discount', $data['discount']) !!}
                            {!! Form::hidden('image_link', $data['image_link']) !!}
                            {!! Form::submit('Save') !!}
                            {!! Form::close() !!}
                            @endif

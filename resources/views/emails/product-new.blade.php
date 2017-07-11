Info about product<br>
@foreach($data['pageError'] as $pageError)
    Error page on:
    @foreach($pageError as $error)
        {{ $error.", " }}
    @endforeach
@endforeach
<br>
Exist: {{ $data['existCount'] }} <br>
New: {{ $data['newCount'] }} <br>


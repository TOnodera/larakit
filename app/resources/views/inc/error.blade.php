@if($errors->any())
    <ul class="error">
        @foreach ($error->all() as $error)
            <li>{{ $error }}</li>            
        @endforeach
    </ul>
@endif
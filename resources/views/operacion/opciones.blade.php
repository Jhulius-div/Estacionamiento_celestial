@foreach ($espacios as $e)
    <option value="{{ $e->id }}">{{ $e->codigo }}</option>
@endforeach

@if (session('status'))
    <p class="flash flash-success">{{ session('status') }}</p>
@endif

@if (session('error'))
    <p class="flash flash-error">{{ session('error') }}</p>
@endif
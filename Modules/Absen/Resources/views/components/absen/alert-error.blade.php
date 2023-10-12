@if (session()->has('error'))
    <div class="alert alert-warning d-flex justify-content-center mt-3" role="alert">
        {{ session('error') }}
    </div>
@endif

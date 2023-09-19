@if (session()->has('success'))
    <div class="alert alert-success d-flex justify-content-center mt-3" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'error!',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    </script>
@endif

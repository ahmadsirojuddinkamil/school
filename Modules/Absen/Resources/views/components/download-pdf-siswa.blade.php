<form action="{{ route('data.absen.download.zip.class') }}" method="POST" class="action-form">
    @csrf

    <li>
        <input type="hidden" value="{{ $saveClassFromObjectCall }}" name="kelas" required>
        <button type="submit" title="pdf"><img src="{{ asset('assets/dashboard/img/icons/purchase1.svg') }}" alt="img" height="30" width="30"></button>
    </li>
</form>

<style>
    .action-form button {
        background: none;
        border: none;
        padding: 0;
        margin: 0;
    }

</style>

<form action="" method="POST" class="action-form">
    @csrf

    <li>
        <input type="hidden" value="" name="kelas" required>
        <button type="submit" title="pdf"><img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img"></button>
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

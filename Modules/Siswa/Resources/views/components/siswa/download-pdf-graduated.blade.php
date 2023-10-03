<li>
    <a data-bs-toggle="modal" data-bs-placement="top" title="pdf" data-bs-target="#modalPdf"><img
            src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img"></a>
</li>

<div class="modal fade" id="modalPdf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Download PDF | Pilih tahun
                    kelulusan!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('siswa.graduated.download.pdf') }}" method="POST" class="action-form">
                @csrf

                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">Tahun lulus</label>
                        <select class="form-select" name="tahun_lulus" required>
                            @foreach ($ListYearGraduated as $year)
                                <option value="{{ $year }}">
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('tahun_lulus')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                    <button type="submit" class="btn btn-submit me-2">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

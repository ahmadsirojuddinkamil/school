<li>
    <a data-bs-toggle="modal" data-bs-placement="top" title="pdf" data-bs-target="#modalPdf">
        <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
    </a>
</li>

<div class="modal fade" id="modalPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPdfLabel">Download PDF | Pilih tahun kelulusan!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label">Tahun lulus</label>
                    <select class="form-select" name="save_year_from_event" id="tahun_lulus_pdf" required>
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

                <button type="button" class="btn btn-submit me-2" id="pdfSubmitButton">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tahunSelectPdf = document.getElementById('tahun_lulus_pdf');
        const modalPdfSubmitButton = document.getElementById('pdfSubmitButton');

        modalPdfSubmitButton.addEventListener('click', function() {
            const selectedYearPdf = tahunSelectPdf.value;
            const url = `{{ route('siswa.graduated.download.pdf.zip', ['save_year_from_event' => 'YEAR_TO_REPLACE']) }}`
                .replace('YEAR_TO_REPLACE', selectedYearPdf);
            window.location.href = url;
        });
    });

</script>

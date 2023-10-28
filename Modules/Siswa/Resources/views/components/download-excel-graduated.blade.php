<li>
    <a data-bs-toggle="modal" data-bs-placement="top" title="excel" data-bs-target="#modalExcel">
        <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
    </a>
</li>

<div class="modal fade" id="modalExcel" tabindex="-1" aria-labelledby="modalExcelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcelLabel">Download Excel | Pilih tahun kelulusan!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label">Tahun lulus</label>
                    <select class="form-select" name="save_year_from_event_excel" id="tahun_lulus_excel" required>
                        @foreach ($listYearGraduated as $year)
                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @error('tahun_lulus_excel')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                <button type="button" class="btn btn-submit me-2" id="excelSubmitButton">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tahunSelectExcel = document.getElementById('tahun_lulus_excel');
        const modalExcelSubmitButton = document.getElementById('excelSubmitButton');

        modalExcelSubmitButton.addEventListener('click', function() {
            const selectedYearExcel = tahunSelectExcel.value;
            const url = `{{ route('siswa.graduated.download.excel.zip', ['save_year_from_event' => 'YEAR_TO_REPLACE']) }}`
                .replace('YEAR_TO_REPLACE', selectedYearExcel);
            window.location.href = url;
        });
    });

</script>

<style>
    .custom-checkbox {
        cursor: pointer;
        display: inline-block;
    }

    /* Sembunyikan checkbox asli */
    .custom-checkbox input {
        display: none;
    }

    .icon-wrapper svg {
        width: 1.2rem; /* size-6 */
        height: 1.2rem;
        transition: all 0.2s ease;
    }

    /* Kondisi Default: Unlocked (Abu-abu) */
    .icon-unlocked { display: block; color: #9ca3af; }
    .icon-locked { display: none; color: #ef4444; }

    /* Kondisi Saat Checked: Locked (Merah/Hijau) */
    .custom-checkbox input:checked ~ .icon-wrapper .icon-unlocked {
        display: none;
    }
    .custom-checkbox input:checked ~ .icon-wrapper .icon-locked {
        display: block;
        color: #10b981; /* Warna hijau sukses */
    }

    /* Efek hover agar interaktif */
    .custom-checkbox:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
</style>

<style>
    /* Transisi warna background yang halus */
    td {
        transition: background-color 0.2s ease;
    }

    /* Memastikan label custom-checkbox memenuhi area klik namun tetap center */
    .custom-checkbox {
        display: flex !important;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        min-height: 2rem;
        margin: 0;
    }

    /* Pastikan warna background td tidak tertutup oleh elemen lain */
    .table-hover tbody tr:hover td {
        background-color: inherit; /* Mencegah hover default menimpa warna hijau */
    }
</style>

<div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
    <table class="table table-bordered table-hover">
        <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
            <tr class="text-center">
                <th rowspan="2" style="vertical-align: middle; min-width: 2rem">NO</td>
                <th rowspan="2" style="vertical-align: middle; min-width: 20rem">PERANGKAT DAERAH</td>
                @foreach ($bulan as $index => $item)
                    <th style="vertical-align: middle; min-width: 2rem">{{ strtoupper($index) }}</tg>
                @endforeach
            </tr>
            <tr>
            @foreach ($bulan as $index => $item)
                {{-- <th class="text-center">
                    <input type="checkbox" class="check-all-month" data-month="{{ $index }}">
                </th> --}}
                <th class="text-center">
                    <label class="custom-checkbox mb-0">
                        <input type="checkbox" class="check-all-month" data-month="{{ $index }}">
                        <span class="icon-wrapper">
                            <svg class="icon-locked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <svg class="icon-unlocked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                    </label>
                </th>
            @endforeach
            </tr>
        </thead>
        <tbody style="font-weight: 500; font-size: 0.9rem; line-height: 1rem;">
            @foreach ($instansi as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->instansi_nama }}</td>
                    @foreach ($bulan as $index => $month)
                        {{-- <td class="text-center" style="vertical-align: middle;">
                            <div class="form-check d-flex justify-content-center align-items-center">
                                <input class="form-check-input check-item-month-{{ $index }}" data-month="{{ $index }}" type="checkbox" value="1" name="permissions[{{ $item->instansi_kode }}][{{ $index }}]" {{ $permission->get($item->instansi_kode)->{'auth_'.$index} ? 'checked' : '' }}>
                            </div>
                        </td> --}}
                        <td class="text-center" style="vertical-align: middle;">
                            <label class="custom-checkbox mb-0">
                                <input class="check-item-month-{{ $index }}" data-month="{{ $index }}" type="checkbox" value="1" name="permissions[{{ $item->instansi_kode }}][{{ $index }}]" {{ $permission->get($item->instansi_kode)->{'auth_'.$index} ? 'checked' : '' }}>
                                <span class="icon-wrapper">
                                    <svg class="icon-locked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    <svg class="icon-unlocked" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </span>
                            </label>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- <script>
    $(document).ready(function() {
        $('.check-all-month').on('change', function() {
            let month = $(this).data('month');
            
            let isChecked = $(this).is(':checked');
            
            $(`.check-item-month-${month}`).prop('checked', isChecked);
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
    // 1. Fungsi untuk mengubah warna background TD
    function updateRowColor(input) {
        const td = $(input).closest('td');
        if ($(input).is(':checked')) {
            td.css('background-color', '#d1fae5'); // Hijau muda (Emerald 100)
        } else {
            td.css('background-color', 'transparent'); // Kembali normal
        }
    }

    // 2. Inisialisasi awal: Warnai TD yang datanya sudah 'checked' dari database
    $('.custom-checkbox input[type="checkbox"]').each(function() {
        updateRowColor(this);
    });

    // 3. Event: Klik manual pada checkbox individu
    $(document).on('change', '.custom-checkbox input[type="checkbox"]', function() {
        updateRowColor(this);
    });

    // 4. Event: Check All per Bulan (Vertical)
    $('.check-all-month').on('change', function() {
        let month = $(this).data('month');
        let isChecked = $(this).is(':checked');
        
        // Pilih semua checkbox di kolom bulan tersebut
        $(`.check-item-month-${month}`).each(function() {
            $(this).prop('checked', isChecked);
            updateRowColor(this); // Update warna sel
        });
    });
});
</script>
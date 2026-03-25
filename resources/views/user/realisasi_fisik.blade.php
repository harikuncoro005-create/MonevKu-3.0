@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/monev/detail?ref={{ Request::get('ref')}}" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-sm table-borderless">
                <tbody class="text-small text-gray-500" style="line-height: 1rem;">
                    @foreach ($ref_kode as $item)
                    <tr class="text-nowrap">
                        <td style="vertical-align: middle; width:10rem">{{ $item['kode_nama'] }}</td>
                        <td style="vertical-align: middle; width:10rem">{{ $item['kode_nomenklatur'] }}</td>
                        <td style="vertical-align: middle">{{ $nomenklatur[$item['kode_nomenklatur']]->nomenklatur_nama }}</td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
    <br>
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>REALISASI FISIK</span>
            </div>
        </div>
    </div>
    @if ($fisik_target)
        <a href="">ok</a>
    @else
        <a href="">no</a>
    @endif
</div>

@endsection
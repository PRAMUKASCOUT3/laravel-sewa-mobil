@extends('layouts.admin')
@include('datatables')
@section('content')
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
<h3>Daftar Transaksi</h3>
</div>
<div class="card-body">
<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Mobil</th>
            <th>Tanggal Rental</th>
            <th>Tanggal Kembali</th>
            <th>Total Denda</th>
            <th>Status</th>
            <th>Tanggal Selesai Sewa</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transaksi as $index => $ts)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $ts->user->name }}</td>
                <td>{{ $ts->car->nama_mobil }}</td>
                <td>{{ Carbon\Carbon::parse($ts->tanggal_rental)->format('d M Y') }}</td>
                <td>{{ Carbon\Carbon::parse($ts->tanggal_kembali)->format('d M Y') }}</td>
                <td>
                    @php
                        $selisihHari = Carbon\Carbon::today()->diffInDays($ts->tanggal_kembali);
                    @endphp
                    @if (now() >= $ts->tanggal_kembali)
                        {{ $selisihHari * $ts->car->denda }}
                    @else
                        Tidak Ada Denda
                    @endif
                </td>
                <td>
                    @if ($ts->status == 'selesai')
                        <a class="btn btn-success" href="#" role="button">Selesai</a>
                    @elseif($ts->status == 'telah di sewa')
                        <a class="btn btn-info" href="#" role="button">Sedang disewa</a>
                    @elseif($ts->status == 'menunggu konfirmasi')
                        <a class="btn btn-secondary" href="#" role="button">Menunggu Konfirmasi</a>
                    @elseif(!$ts->status)
                        <a class="btn btn-primary" href="#" role="button">Belum bayar</a>
                    @endif
                </td>
                <td>
                    {{ $ts->tanggal_selesai }}
                </td>

                <td>
                    @if ($ts->status == 'selesai')
                        <a class="btn btn-success" href="#" role="button">Selesai</a>
                    @elseif($ts->status == 'telah di sewa')
                        <a href="{{ Route('admin.transaksi.selesai', $ts->id) }}"
                            class="btn btn-success">Selesai</a>
                    @elseif($ts->status == 'menunggu konfirmasi')
                        <form action="{{ Route('transaksi.confirmation', $ts->id) }}" method="post">
                            @csrf
                            @method('put')

                            <button type="submit" class="btn btn-primary">Konfirmasi</button>
                        </form>
                    @elseif(!$ts->status)

                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
</div>
@endsection

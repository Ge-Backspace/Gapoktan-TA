<html>
<head>
	<title>Export Laporan Stok Lumbung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
</head>
<body>

	<div class="container">
		<center>
			<h4>LAPORAN STOK LUMBUNG GAPOKTAN {{strtoupper($gapoktan->nama)}} {{date('d-m-Y', strtotime($tanggal_awal))}} - {{date('d-m-Y', strtotime($tanggal_akhir))}}</h4>
		</center>
        <p><b>Total : {{ count($datas) }} data</b></p>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>No</th>
					<th>Poktan</th>
					<th>Komoditas</th>
					<th>Jumlah</th>
					<th>Tahun Banper</th>
					<th>Tanggal Lapor</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($datas as $d)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ $d->nama_poktan }}</td>
					<td>{{ $d->komoditas }}</td>
					<td>{{ $d->jumlah }} Hektar</td>
					<td>{{ $d->tahun_banper }}</td>
					<td>{{ date('d-m-Y', strtotime($d->tanggal_lapor)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

</body>
</html>

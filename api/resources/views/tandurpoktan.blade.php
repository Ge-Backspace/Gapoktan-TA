<html>
<head>
	<title>Export Laporan Tandur</title>
</head>
<body>

	<div class="container">
		<center>
			<h4>LAPORAN TANDUR POKTAN {{strtoupper($poktan->nama)}} {{date('d-m-Y', strtotime($tanggal_awal))}} - {{date('d-m-Y', strtotime($tanggal_akhir))}}</h4>
		</center>
        <p><b>Total : {{ count($datas) }} data</b></p>
		<table class='table table-bordered'>
			<thead>
				<tr>
					<th>No</th>
					<th>Tanaman</th>
					<th>Luas Tandur</th>
					<th>Tanggal Tandur</th>
					<th>Tanggal Panen</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($datas as $d)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{ $d->tanaman }}</td>
					<td>{{ $d->luas_tandur }} Hektar</td>
					<td>{{ date('d-m-Y', strtotime($d->tanggal_tandur)) }}</td>
					<td>{{ date('d-m-Y', strtotime($d->tanggal_panen)) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

</body>
</html>

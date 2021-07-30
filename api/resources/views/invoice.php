<!DOCTYPE html>
<html>
<head>
	<title>YES</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>YES</h4>
		<h6><a target="_blank" href="https://www.malasngoding.com/membuat-laporan-…n-dompdf-laravel/">www.malasngoding.com</a></h5>
	</center>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>Title</th>
				<th>Description</th>
				<th>Stock</th>
				<th>Price</th>
				
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($product as $p)
			<tr>
				<td>{{ $i++ }}</td>
				<td>{{$p->title}}</td>
				<td>{{$p->description}}</td>
				<td>{{$p->stock}}</td>
				<td>{{$p->price}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>
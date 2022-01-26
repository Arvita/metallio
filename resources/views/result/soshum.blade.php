<!DOCTYPE html>
<html>
<head>
	<title>Saintek - Metallio 2022</title>
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
		<h5>Soshum - Metallio 2022</h4>
	</center>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th rowspan="2" width="5%">No</th>
				<th rowspan="2">Name</th>
				<th colspan="5" style="text-align: center">TPS</th>
				<th colspan="4" style="text-align: center">TKA</th>
				<th rowspan="2">Total</th>
			</tr>
			<tr>
				<th>PBM</th>
				<th>PPU</th>
				<th>PK</th>
				<th>PU</th>
				<th>BING</th>
				<th>SEJARAH</th>
				<th>EKONOMI</th>
				<th>GEOGRAFI</th>
				<th>SOSIOLOGI</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1; $j=0; @endphp
            {{-- @php dd($vals) @endphp --}}
			@foreach($vals as $p => $value)
			<tr>
                {{-- @foreach($value as $s => $values) --}}
				<td>{{ $j }}</td>
				<td>{{$value['name']}}</td>
				<td>{{$value['pbm']}}</td>
				<td>{{$value['ppu']}}</td>
				<td>{{$value['pk']}}</td>
				<td>{{$value['pu']}}</td>
				<td>{{$value['bing']}}</td>
				<td>{{$value['sejarah']}}</td>
				<td>{{$value['ekonomi']}}</td>
				<td>{{$value['geografi']}}</td>
				<td>{{$value['sosiologi']}}</td>
				<td>{{$value['score']}}</td>
                {{-- @endforeach --}}
                @php $j++; @endphp
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>
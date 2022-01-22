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
		<h5>Saintek - Metallio 2022</h4>
	</center>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th width="5%">No</th>
				<th>Name</th>
				<th>Score</th>
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
				<td>{{$value['score']}}</td>
                {{-- @endforeach --}}
                @php $j++; @endphp
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>
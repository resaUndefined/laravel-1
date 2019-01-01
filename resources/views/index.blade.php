@extends('layout.base')

@section('title')
	Halaman Blog Index
@stop

@section('content')
	<h1>Halaman Posts</h1>
	@if(count($posts) > 0)
		<ul>
			@foreach($posts as $post)
				<li><a href="{{ route('post.show', $post['id']) }}">{{ $post['title'] }}</a></li>
			@endforeach
		</ul>
	@else
	<p>Tidak ada data</p>
	@endif
@endsection
{{-- <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ini adalah halam index</title>
</head>
<body>
	
	
</body>
</html> --}}
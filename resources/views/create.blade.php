@extends('layout.base')

@section('title')
	Halaman Create
@endsection

@section('content')
	<h1>Halaman Create Post</h1>
	<form action="{{ route('post.store') }}" method="post">
		{{ csrf_field() }}
		<label for="">Title</label>
		<br>
		<input type="text" name="title" id="title"/>
		<br>
		<label for="">Body</label>
		<br>
		<textarea id="body" name="body"></textarea>
		<br>
		<button type="submit">Submit</button>
	</form>
@endsection

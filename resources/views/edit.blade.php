@extends('layouts.app')

@section('content')
<div class="row justify-content-center ml-0 mr-0 h-100">
    <div class="card w-100">
        <div class="card-header d-flex justify-content-between">メモ編集
            <form method="POST" action="{{ route('delete', ['id'=>$memo['id']] ) }}" id="delete-form">
                @csrf
                <button class="p-0 mr-2 border-0"><i id="delete-button" class="fas fa-trash"></i></button>
            </form>
        </div>
        <div class="card-body">
            <form action="{{ route('update', ['id' => $memo['id']] ) }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value={{ $user['id'] }}>
                <div class="form-group">
                <textarea class="form-control copytext" name="content" rows="10">{{ $memo['content'] }}</textarea>
                </div>
                <div class="form-group">
                    <select class="form-control" name="tag_id" id="">
                       @foreach($tags as $tag)
                       <option value="{{ $tag['id'] }}" {{$tag['id'] == $memo['tag_id'] ? "selected" : "" }}>{{ $tag['name']}}</option>
                       @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">更新</button>
            </form>
            <button class="btn btn-secondary btn-lg mt-3" onclick="clickhundler.copytext()">コピー</div>
        </div>
    </div>

</div>
@endsection

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Memo;
use App\Tag;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // // rogin情報から自身の情報を取得
        // $user = \Auth::user();

        // // メモ一覧を取得する sqlの書き方をlaravelに落としこんだ記載をしている
        // // ASC:昇順 DESC:降順
        // $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        // return view('create', compact('user', 'memos'));

        return view('create');
    }

    public function create()
    {
        // //ログインしているユーザー情報を渡す
        // $user = \Auth::user();

        // $memos = Memo::where('user_id', $user['id'])->where('status', 1)->orderBy('updated_at', 'DESC')->get();
        // return view('create', compact('user', 'memos'));

        return view('create');
    }

    public function store(Request $request)
    {
        // postデータを整形？
        // $data = $request->all();
        // allよりonlyを使う方がデータ送信の際に不要データを何らかのバグで取得することがなくなる
        $data = $request->only(['tag', 'user_id', 'content']);
        // POSTされたデータをDBに挿入

        // 先にタグにインサート
        // もし、同じタグが投稿された場合は新しくtagsテーブルにinsertせず、既存のタグを紐付ける
        // existはtrue,falseで戻り値
        // $exist_tag = Tag::where('user_id', $data['user_id'])->where('name', $data['tag'])->exists();
        // if(empty($exist_tag)) {}

        if ($default_tag = Tag::where('name', $data['tag'])->where('user_id', $data['user_id'])->first()) {
            // 同じタグがあった場合
            $tag_id = $default_tag['id'];
        } else {
            // 同じタグがない場合
            $tag_id = Tag::insertGetId(
                [
                    // insertGetId: 戻値にインサートしたデータのIdが帰ってくる
                    'name' => $data['tag'],
                    'user_id' => $data['user_id']
                ]
            );
        }

        // MemoTag::insert(['memo'=> $memo_id, 'tag_id' => $tag_id]);

        // タグのidが判明
        // タグのIDをmemostableに入れる

        // MEMOモデルDBへ保存する命令を出す
        // insertGetIdはModelの基本機能
        $memo_id = Memo::insertGetId(
            [
                'content' => $data['content'],
                'user_id' => $data['user_id'],
                'tag_id' => $tag_id,
                'status' => 1
            ]
        );

        // リダイレクト処理 -> 別のページに遷移させる処理
        return redirect()->route('home')->with('success', 'メモの作成が成功しました');
    }

    public function edit($id)
    {
        $user = \Auth::user();
        // $memos = Memo::where('status', 1)->where('user_id', $user['id'])->orderBy('updated_at', 'DESC')->get();
        // // firstは1行のみ取得
        $memo = Memo::where('status', 1)->where('id', $id)->where('user_id', $user['id'])->first();
        // // 取得したメモをviewに渡す
        // $tags = Tag::where('user_id', $user['id'])->get();
        // return view('edit', compact('memos', 'memo', 'user', 'tags'));

        return view('edit', compact('memo'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        Memo::where('id', $id)->update(
            [
                'content' => $data['content'],
                'tag_id' => $data['tag_id']
            ]
        );

        return redirect()->route('home')->with('success', 'メモを更新しました');
    }

    public function delete(Request $request, $id)
    {
        // status:2を削除データとする（DDには残す)論理削除と呼ぶ
        Memo::where('id', $id)->update(['status' => 2]);
        // 物理的に削除
        // Memo::where('id', $id)->delete();

        return redirect()->route('home')->with('success', 'メモの削除が完了しました');
    }
}

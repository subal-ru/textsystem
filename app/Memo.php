<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    //
    public function myMemo($user_id)
    {

        // タグの取得(queryparametorから取得 クエリーパラメータは右のやつ /?tag=test  )
        $tag = \Request::query('tag');

        // タグがなければその人が持ってるメモを全て取得
        if (empty($tag)) {
            return $this::select('memos.*')->where('user_id', $user_id)->where('status', 1)->get();
        } else {
            // もしタグの指定があればタグで絞る ->where(tag)がクエリパラメータで取得したものに一致
            $memos = $this::select('memos.*')
                // leftJoinはテーブルの結合
                ->leftJoin('tags', 'tags.id', '=', 'memos.tag_id') //memosテーブルにtagsテーブルを結合する
                ->where('tags.name', $tag)
                ->where('tags.user_id', $user_id)
                ->where('memos.user_id', $user_id)
                ->where('status', 1)
                ->get();

            // 外部結合・内部結合
            // 外部結合：leftとrightがありベースとなるものを指す。条件に合わないデータも残す

            return $memos;
        }
    }
}

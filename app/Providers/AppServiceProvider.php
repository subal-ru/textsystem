<?php
// ビューコンポーザー：viewが動くときに動作をさせることができる。
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Memo;
use App\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 全てのめそっどが呼ばれる前に呼ばれるメソッド
        view()->composer('*', function ($view) {
            $user = \Auth::user();

            // インスタンス化
            $memoModel = new Memo();
            $memos = $memoModel->myMemo(\Auth::id());
            // タグ取得
            $tagModel = new Tag();
            $tags = $tagModel->where('user_id', \Auth::id())->get();

            $current_tag = \Request::query("tag");

            if (empty($current_tag)) {
                // $view->with('user', $user)->with('memos', $memos)->with('tags', $tags);
                $view->with('user', $user)->with('memos', $memos)->with('tags', $tags)->with('current_tag', false);
            } else {
                $view->with('user', $user)->with('memos', $memos)->with('tags', $tags)->with('current_tag', $current_tag);
            }
        });
    }
}

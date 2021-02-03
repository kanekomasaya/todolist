<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
  public function showCreateForm()
  {
      return view('folders.create');
  }

  public function create(Request $request)
{
    $rules = [
        'title' => 'required|max:20',
    ];
    $this->validate($request, $rules);
    // フォルダモデルのインスタンスを作成する
    $folder = new Folder();
    // タイトルに入力値を代入する
    $folder->title = $request->title;
    $folder->user_id = Auth::user()->id;
    // インスタンスの状態をデータベースに書き込む
    Auth::user()->folders()->save($folder);

    return redirect()->route('tasks.index', [
        'id' => $folder->id,
    ]);
}
}

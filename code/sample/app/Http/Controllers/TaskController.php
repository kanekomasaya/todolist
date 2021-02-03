<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
  public function index(int $id)
  {
      // 特定のフォルダを取得する
      $folders = Auth::user()->folders()->get();
      // 選ばれたフォルダを取得する
      $current_folder = Folder::find($id);
      // 選ばれたフォルダに紐づくタスクを取得する
      //$tasks = Task::where('folder_id', $current_folder->id)->get();
      $tasks = $current_folder->tasks()->get();
      return view('tasks.index', ['folders' => $folders, 'current_folder_id' => $id, 'tasks' => $tasks]);
  }

  public function showCreateForm(int $id)
  {
    return view('tasks.create', [
        'folder_id' => $id
    ]);
  }

  public function create(int $id, Request $request)
  {
    $rules = [
        'title' => 'required|max:20',
        'due_date' => 'required|date|after_or_equal:today',
    ];

    $this->validate($request, $rules);

    $current_folder = Folder::find($id);

    $task = new Task();
    $task->title = $request->title;
    $task->due_date = $request->due_date;

    $current_folder->tasks()->save($task);

    return redirect()->route('tasks.index', [
        'id' => $current_folder->id,
    ]);
  }

  public function showEditForm(int $id, int $task_id)
  {
    $task = Task::find($task_id);

    return view('tasks.edit', [
        'task' => $task,
    ]);
  }

  public function edit(int $id, int $task_id, Request $request)
  {

    // 1
    $task = Task::find($task_id);

    // 2
    $task->title = $request->title;
    $task->status = $request->status;
    $task->due_date = $request->due_date;
    $task->save();

    // 3
    return redirect()->route('tasks.index', [
        'id' => $task->folder_id,
    ]);
  }

  public function logout(){
  Auth::logout();
  return redirect()->route('home');
  }
}

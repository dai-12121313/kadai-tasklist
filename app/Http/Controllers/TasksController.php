<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class tasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        return view('welcome', $data);
    }

    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:10',
        ]);
        
        //dd($request->user());
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,            
        ]);

        return back();
    }

    public function show($id)
    {
        if (\Auth::check()) {
            $task = Task::find($id);
    
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
    }

    public function edit($id)
    {
        if (\Auth::check()) {
            $task = Task::find($id);
    
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:10',
        ]);
        $task = Task::find($id);
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        return redirect('/');
    }

    public function destroy($id)
    {
        if (\Auth::check()) {
            $task = Task::find($id);
            $task->delete();
    
            return redirect('/');
        }
    }
}
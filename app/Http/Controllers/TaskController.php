<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::query();

            if ($request['order'][0]['column'] == '0') {
                $tasks = $tasks->orderBy('priority');
            }

            return DataTables::of($tasks)
                ->addColumn('id', function (Task $task) {
                    $data = array(
                        'id' => $task->id
                    );
                    return $data;
                })
                ->addColumn('name', function (Task $task) {
                    $data = array(
                        'name' => $task->name
                    );
                    return $data;
                })
                ->addColumn('timestamp', function (Task $task) {
                    $data = array(
                        'timestamp' => Carbon::parse($task->timestamp)->format('Y-m-d H:i A')
                    );
                    return $data;
                })
                ->addColumn('priority', function (Task $task) {
                    $data = array(
                        'priority' => $task->priority
                    );
                    return $data;
                })
                ->addColumn('action', function (Task $task) {
                    $data = array(
                        'id' => $task->id,
                        'status' => $task->status,
                        'name' => $task->name,
                    );
                    return $data;
                })
                ->setRowAttr([
                    'data-ids' => function($task) {
                        return $task->id;
                    },
                ])
                ->filter(function ($query){
                    if(isset(request()->search['value']) && request()->search['value'] != null) {
                        $searchValue = request()->search['value'];
                        $query->where('name','like', '%'.$searchValue.'%');
                    }
                })
                ->orderColumn('name', 'name $1')
                ->orderColumn('status', 'status $1')
                ->make(true);
        }
    }

    public function updateOrder(Request $request)
    {
        $ids = $request->data;
        try {
            $order = 1;
            foreach ($ids as $id) {
                Task::where('id', $id)->update(array(
                    'priority' => $order
                ));
                $order++;
            }
        } catch (QueryException $e) {
            return response()->json(['status'=>500, 'message'=>'Server Error']);
        }
        return response()->json(['status'=>200, 'message'=>'Record Updated']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:191',
            'timestamp' => 'required',
        ]);

        try {
            $task = new Task();
            $task->user_id = Auth::user()->id;
            $task->name = $request->name;
            $task->timestamp = Carbon::parse($request->timestamp)->format('Y-m-d h:i:s');
            $task->priority = Task::select('priority')->max('priority') + 1;
            $task->save();

            return redirect()->back()->with('message', 'Task Created Successfully.');
        } catch (QueryException $th) {
            return redirect()->back()->with('message', 'Server error!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('pages.tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|max:191',
            'timestamp' => 'required',
        ]);

        try {
            $task->name = $request->name;
            $task->timestamp = Carbon::parse($request->timestamp)->format('Y-m-d h:i:s');
            $task->save();

            return redirect()->back()->with('message', 'Task updated Successfully.');
        } catch (QueryException $th) {
            return redirect()->back()->with('message', 'Server error!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task Deleted Successfully.']);
    }
}

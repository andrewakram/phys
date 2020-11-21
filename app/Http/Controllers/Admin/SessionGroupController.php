<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\SessionGroupRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SessionGroupController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('SessionGroup')->where('deleted',0)->paginate(20);
        $groups = $this->indexRepository->index('Group')->where('deleted',0)->get();
        $sessions = $this->indexRepository->index('Session')->where('deleted',0)->get();
        return view('sessions_groups.index', compact('results', 'groups', 'sessions'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $this->indexRepository
            ->create("SessionGroup", $request->all());
        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function update(Request $request)
    {
//        $this->validate($request, [
//            'name' => 'required|unique:sessions_groups,name,' . "$request->model_id",
//        ]);
        $this->indexRepository
            ->update("SessionGroup", $request->except('_token', 'model_id'), $request->model_id);
        return redirect(route('sessions_groups'))->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("SessionGroup", $request->model_id);
        return redirect(route('sessions_groups'))->with('success', 'تمت العملية بنجاح');
    }

    public function searchSessionGroups(Request $request)
    {
        $results = $this->indexRepository->index('SessionGroup')
            ->where('deleted',0)
            ->where('group_id',$request->group_id)
            ->paginate(20);
        $groups = $this->indexRepository->index('Group')->where('deleted',0)->get();
        $sessions = $this->indexRepository->index('Session')->where('deleted',0)->get();
        return view('sessions_groups.index', compact('results', 'groups', 'sessions'));
    }



}

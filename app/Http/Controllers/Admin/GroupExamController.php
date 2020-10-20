<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\GroupExamRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupExamController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('GroupExam')->with('exam')->with('group')->paginate(20);
        $groups = $this->indexRepository->index('Group')->get();
        $exams = $this->indexRepository->index('Exam')->get();
        return view('groups_exams.index', compact('results', 'groups', 'exams'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $this->indexRepository
            ->create("GroupExam", $request->all());
        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function update(Request $request)
    {
//        $this->validate($request, [
//            'name' => 'required|unique:groups_exams,name,' . "$request->model_id",
//        ]);
        $this->indexRepository
            ->update("GroupExam", $request->except('_token', 'model_id'), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("GroupExam", $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function searchGroupExams(Request $request)
    {
        $results = $this->indexRepository->index('GroupExam')
            ->where('group_id',$request->group_id)
            ->with('exam')
            ->with('group')
            ->paginate(20);
        $groups = $this->indexRepository->index('Group')->get();
        $exams = $this->indexRepository->index('Exam')->get();
        return view('groups_exams.index', compact('results', 'groups', 'exams'));
    }



}

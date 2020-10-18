<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\GroupRepositoryInterface;
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
        return view('groups_exams.index', compact('results', 'groups','exams'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $this->indexRepository->checkIfExists("Group", "name", $request->name);
        if ($user)
            return back()->with('error', 'رقم الهاتف موجود من قبل');
        else {
            $this->indexRepository
                ->create("Group", array_merge($request->all(), ['group_num' => rand(100,999) .' - '. Str::random(3)]));
            return back()->with('success', 'تمت العملية بنجاح');
        }

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:groups_exams,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Group", $request->except('_token','model_id'), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("Group",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

}

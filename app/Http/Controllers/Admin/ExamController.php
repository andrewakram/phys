<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\ExamRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Exam')->with('questions')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->get();
        return view('exams.index', compact('results', 'stages'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $this->indexRepository->checkIfExists("Exam", "name", $request->name);
        if ($user)
            return back()->with('error', 'رقم الهاتف موجود من قبل');
        else {
            $this->indexRepository->create("Exam", array_merge($request->all(), ['exam_num' => rand(100,999) .' - '. Str::random(3)]));
            return back()->with('success', 'تمت العملية بنجاح');
        }

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:exams,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Exam", $request->except('_token','model_id'), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("Exam",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function searchExams(Request $request)
    {
        $results = $this->indexRepository->index('Exam')
            ->where('stage_id',$request->stage_id)
            ->with('questions')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->get();
        return view('exams.index', compact('results', 'stages'));
    }

    public function show($id)
    {
        $user = $this->userRepository->profile($id);
        return view('admin.exams.show', compact('user'));
    }


}

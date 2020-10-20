<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\QuestionRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository
            ->index('Question')
            ->orderBy('id','desc')
            ->with('exam')
            ->with('answers')
            ->paginate(20);
        $exams = $this->indexRepository
            ->index('Exam')
            ->with('stage')
            ->get();
        return view('questions.index', compact('results', 'exams'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $question =$this->indexRepository->create("Question", $request->all());
        for($i=0; $i<sizeof($request->answer); $i++){
            $is_true = $request->trueValue == $request->answer[$i] ? 1 : 0;
            $this->indexRepository
                ->create("Answer", ['answer' => $request->answer[$i] , 'is_true' => $is_true,'question_id' =>$question->id]);
        }

        return back()->with('success', 'تمت العملية بنجاح');


    }

    public function update(Request $request)
    {
        $q= $request->except('_token', 'model_id','answer','is_true','trueValue');
        $this->indexRepository
            ->update("Question", $q, $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("Question", $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function searchQuestions(Request $request)
    {
        $results = $this->indexRepository
            ->index('Question')
            ->orderBy('id','desc')
            ->where('exam_id',$request->exam_id)
            ->with('exam')
            ->with('answers')
            ->paginate(20);
        $exams = $this->indexRepository
            ->index('Exam')
            ->with('stage')
            ->get();
        return view('questions.index', compact('results', 'exams'));
    }

}

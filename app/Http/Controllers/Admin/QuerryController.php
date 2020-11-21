<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\QuerryRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QuerryController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository
            ->index('Querry')
            ->where('deleted',0)
            ->orderBy('id','desc')
            ->with('test')
            ->with('replies')
            ->paginate(20);
        $tests = $this->indexRepository
            ->index('Test')
            ->where('deleted',0)
            ->with('stage')
            ->get();
        return view('querries.index', compact('results', 'tests'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $querry =$this->indexRepository->create("Querry", $request->all());
        for($i=0; $i<sizeof($request->answer); $i++){
            $is_true = $request->trueValue == $request->answer[$i] ? 1 : 0;
            $this->indexRepository
                ->create("Reply", ['answer' => $request->answer[$i] , 'is_true' => $is_true,'querry_id' => $querry->id]);
        }

        return back()->with('success', 'تمت العملية بنجاح');


    }

    public function update(Request $request)
    {
        $q= $request->except('_token', 'model_id','answer','is_true','trueValue');
        $this->indexRepository
            ->update("Querry", $q, $request->model_id);
        return redirect(route('querries'))->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("Querry", $request->model_id);
        return redirect(route('querries'))->with('success', 'تمت العملية بنجاح');
    }

    public function searchQuerries(Request $request)
    {
        $results = $this->indexRepository
            ->index('Querry')
            ->where('deleted',0)
            ->orderBy('id','desc')
            ->where('test_id',$request->test_id)
            ->with('test')
            ->with('replies')
            ->paginate(20);
        $tests = $this->indexRepository
            ->index('Test')
            ->where('deleted',0)
            ->with('stage')
            ->get();
        return view('querries.index', compact('results', 'tests'));
    }

}

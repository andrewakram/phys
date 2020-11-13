<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\StageRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class StageController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Stage')->where('deleted',0)->paginate(20);
        return view('stages.index', compact('results'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $this->indexRepository->checkIfExists("Stage", "name", $request->name);
        if ($user)
            return back()->with('error', 'رقم الهاتف موجود من قبل');
        else {
            $this->indexRepository->create("Stage", $request->all());
            return back()->with('success', 'تمت العملية بنجاح');
        }

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:stages,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Stage", $request->except('_token','model_id'), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        
        $this->indexRepository->delete("Stage",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

}

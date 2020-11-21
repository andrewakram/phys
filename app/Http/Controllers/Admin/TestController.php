<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\TestRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Test')->where('deleted',0)->with('querries')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->where('deleted',0)->get();
        return view('tests.index', compact('results', 'stages'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $this->indexRepository->checkIfExists("Test", "name", $request->name);
        if ($user)
            return back()->with('error', 'رقم الهاتف موجود من قبل');
        else {
            $this->indexRepository->create("Test", array_merge($request->all(), ['test_num' => rand(100,999) .' - '. Str::random(3)]));
            return back()->with('success', 'تمت العملية بنجاح');
        }

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:tests,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Test", $request->except('_token','model_id'), $request->model_id);
        return redirect(route('tests'))->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("Test",$request->model_id);
        return redirect(route('tests'))->with('success', 'تمت العملية بنجاح');
    }

    public function searchTests(Request $request)
    {
        $results = $this->indexRepository->index('Test')
            ->where('deleted',0)
            ->where('stage_id',$request->stage_id)
            ->with('querries')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->get();
        return view('tests.index', compact('results', 'stages'));
    }

    public function show($id)
    {
        $user = $this->userRepository->profile($id);
        return view('admin.tests.show', compact('user'));
    }


}

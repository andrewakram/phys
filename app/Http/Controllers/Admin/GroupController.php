<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\GroupRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Group')->with('stage')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->get();
        return view('groups.index', compact('results', 'stages'));
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
            'name' => 'required|unique:groups,name,' . "$request->model_id",
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

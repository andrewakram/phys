<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\SessionRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class SessionController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Session')->where('deleted',0)->paginate(20);
        return view('sessions.index', compact('results'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->indexRepository->create("Session", $request->all());
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:sessions,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Session", $request->except('_token','model_id'), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {

        $this->indexRepository->delete("Session",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

}

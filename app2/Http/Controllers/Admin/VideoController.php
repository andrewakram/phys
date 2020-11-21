<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\VideoRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class VideoController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Video')->where('deleted',0)->paginate(20);
        return view('videos.index', compact('results'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->indexRepository->create("Video", $request->all());
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:videos,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Video", $request->except('_token','model_id'), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {

        $this->indexRepository->delete("Video",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

}

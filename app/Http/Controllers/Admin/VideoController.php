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
        $sessions = $this->indexRepository->index('Session')->where('deleted',0)->get();
        return view('videos.index', compact('results','sessions'));
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

    public function searchVideos(Request $request)
    {
        $videos_ids = $this->indexRepository->index('SessionVideo')
            ->where('session_id',$request->session_id)
            ->where('deleted',0)
            ->pluck('video_id');
        $results = $this->indexRepository->index('Video')
            ->whereIn('id',$videos_ids)
            ->where('deleted',0)
            ->paginate(20);
        $sessions = $this->indexRepository->index('Session')->where('deleted',0)->get();
        return view('videos.index', compact('results','sessions'));
    }

}

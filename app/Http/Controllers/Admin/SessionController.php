<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\SessionRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use App\Models\SessionTest;
use App\Models\SessionVideo;
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
        $stages = $this->indexRepository->index('Stage')->where('deleted',0)->get();
        $videos = $this->indexRepository->index('Video')->where('deleted',0)->get();
        $tests = $this->indexRepository->index('Test')->where('deleted',0)->get();
        return view('sessions.index', compact('results','stages','videos','tests'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $session = $this->indexRepository->create("Session", $request->all());
        $this->indexRepository->create("SessionVideo", ['session_id' => $session->id , 'video_id' => $request->video_id1]);
        $this->indexRepository->create("SessionVideo", ['session_id' => $session->id , 'video_id' => $request->video_id2]);
        $this->indexRepository->create("SessionTest", ['session_id' => $session->id , 'test_id' => $request->test_id1]);
        $this->indexRepository->create("SessionTest", ['session_id' => $session->id , 'test_id' => $request->test_id2]);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function update(Request $request)
    {
        $this->indexRepository
            ->update("Session", $request->except('_token','model_id'), $request->model_id);
        $f_video = SessionVideo::where('session_id',$request->model_id)->first();
        $f_video->video_id = $request->video_id1;
        $f_video->save();
        $s_video = SessionVideo::where('session_id',$request->model_id)->orderBy('id','desc')->first();
        $s_video->video_id = $request->video_id2;
        $s_video->save();
        $f_test = SessionTest::where('session_id',$request->model_id)->first();
        $f_test->test_id = $request->test_id1;
        $f_test->save();
        $s_test = SessionTest::where('session_id',$request->model_id)->orderBy('id','desc')->first();
        $s_test->test_id = $request->test_id2;
        $s_test->save();
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {

        $this->indexRepository->delete("Session",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

}

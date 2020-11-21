<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\PromocodeRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PromocodeController extends Controller
{
    protected $indexRepository;

    public function __construct(IndexRepositoryInterface $indexRepository)
    {
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('Promocode')->where('deleted',0)->with('user')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->where('deleted',0)->get();
        $groups = $this->indexRepository->index('Group')->where('deleted',0)->get();
        $users = $this->indexRepository->index('User')->where('deleted',0)->get();
        return view('promocodes.index', compact('results', 'stages','groups','users'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $this->indexRepository->checkIfExists("Promocode", "code", $request->code);
        if ($user)
            return back()->with('error', 'رقم الهاتف موجود من قبل');
        else {
            $this->indexRepository->create("Promocode", $request->all());
            return back()->with('success', 'تمت العملية بنجاح');
        }

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:promocodes,name,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("Promocode", $request->except('_token','model_id'), $request->model_id);
        return redirect(route('promocodes'))->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("Promocode",$request->model_id);
        return redirect(route('promocodes'))->with('success', 'تمت العملية بنجاح');
    }

    public function searchPromocodes(Request $request)
    {
        $users_ids = $results = $this->indexRepository->index('User')
            ->where('deleted',0)
            ->where('group_id',$request->group_id)
            ->pluck('id');
        $results = $this->indexRepository->index('Promocode')
            ->where('deleted',0)
            ->whereIn('user_id',$users_ids)
            ->with('user')->paginate(20);
        $stages = $this->indexRepository->index('Stage')->where('deleted',0)->get();
        $groups = $this->indexRepository->index('Group')->where('deleted',0)->get();
        $users = $this->indexRepository->index('User')->where('deleted',0)->get();
        return view('promocodes.index', compact('results', 'stages','groups','users'));
    }

    public function show($id)
    {
        $user = $this->userRepository->profile($id);
        return view('admin.promocodes.show', compact('user'));
    }

    public function getGroups(Request $request){
        //ajax
        $groups=$this->indexRepository->index('Group')
            ->where('deleted',0)
            ->where('stage_id',$request->stage_id)
            ->get();
        return response()->json($groups);
    }

    public function getUsers(Request $request){
        //ajax
        $users=$this->indexRepository->index('User')
            ->where('deleted',0)
            ->where('group_id',$request->group_id)
            ->get();
        return response()->json($users);
    }


}

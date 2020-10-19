<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Interfaces\Admin\UserRepositoryInterface;
use App\Http\Controllers\Interfaces\IndexRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userRepository;
    protected $indexRepository;

    public function __construct(UserRepositoryInterface $userRepository, IndexRepositoryInterface $indexRepository)
    {
        $this->userRepository = $userRepository;
        $this->indexRepository = $indexRepository;
    }

    public function index()
    {
        $results = $this->indexRepository->index('User')->with('group')->paginate(20);
        $groups = $this->indexRepository->index('Group')->get();
        return view('users.index', compact('results', 'groups'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = $this->indexRepository->checkIfExists("User", "phone", $request->phone);
        if ($user)
            return back()->with('error', 'رقم الهاتف موجود من قبل');
        else {
            $this->indexRepository
                ->create("User", array_merge($request->all(), ['password' => Hash::make($request->password)]));
            return back()->with('success', 'تمت العملية بنجاح');
        }

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|unique:users,phone,' . "$request->model_id",
        ]);
        $this->indexRepository
            ->update("User", array_merge($request->except('_token','model_id'), ['password' => Hash::make($request->password)]), $request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');

    }

    public function delete(Request $request)
    {
        $this->indexRepository->delete("User",$request->model_id);
        return back()->with('success', 'تمت العملية بنجاح');
    }

    public function show($id)
    {
        $user = $this->userRepository->profile($id);
        return view('admin.users.show', compact('user'));
    }

    public function changeStatus(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id'
        ]);

        $this->indexRepository->changStatus("User",$request->user_id);
        //return back()->with('success', 'تمت العملية بنجاح');
    }

    public function search(Request $request)
    {
        $users = $this->userRepository->search($request);
        $type = '';
        return view('admin.users.index', compact('users', 'type'));
    }
}

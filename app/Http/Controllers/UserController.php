<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserAddRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return view('user');
    }
    public function inputSearch(){
        $group_role = User::distinct()->pluck('group_role');
        $active = User::distinct()->pluck('is_active');
        return response()->json([
            'group_role' => $group_role,
            'active' => $active,
        ], 200);
    }
    public function show(Request $request)
    {
        $numberUserPerPage = 10;
        $query = User::query()->where('is_delete', 0);

        $filters= $this->filters($request);
        
        $query->searchUser($filters);

            $count = $query->get()->count();
            $data = $query->orderBy('id', 'desc')->paginate($numberUserPerPage);
            return response()->json([
                'numberUserPerPage' => $numberUserPerPage,
                'count' =>$count,
                'data' =>$data->getCollection(),
                'paginate' => $data,
                ], 200);
    }

    public function delete(Request $request)
    {
       
        // if($request->id == Auth::user()->id){
        //     return response()->json([
        //         'error' => "Không thể xóa chính mình"
        //         ], 400);
        // }
        $delete = User::where('id',$request->id)->first();
        if(is_null($delete)){
            return response()->json([
                'error' => "Không tồn tại"
                ], 400);
        }
        $delete->is_delete = 1;
        $delete->save();

        return response()->json([
            'msg' => 'Xóa thành công thành viên '.$delete->name,
        ], 200);
    }

    public function changeStatus(Request $request)
    {

        // if($request->id == Auth::user()->id){
        //     return response()->json([
        //         'error' => "Không thể khóa chính mình"
        //         ], 400);
        // }
        $changeStatus = User::where('id',$request->id)->first();
        if(is_null($changeStatus)){
            return response()->json([
                'error' => "Không tồn tại"
                ], 400);
        }
        if($changeStatus->is_active === 0){
            $changeStatus->is_active = 1;
            $msg = 'Mở khóa thành viên '.$changeStatus->name;
        }else{
            $changeStatus->is_active = 0;
            $msg = 'Khóa thành viên '.$changeStatus->name;
        }        
        $changeStatus->save();

        return response()->json([
            'msg' => $msg,
        ], 200);
    }

    public function add(UserAddRequest $request)
    {

        $user = new User();
        $user->name = $request->add_name;
        $user->email = $request->add_email;
        $user->password = Hash::make($request->add_password);
        $user->group_role = $request->add_group_role;
        // $user->is_active = 0;
        // if($request->add_status === 'on')
        // {
        //     $user->is_active = 1;
        // }

        $user->is_active = $request->add_status;
        $user->save();

        return response()->json([
            'msg' => 'Thêm thành công thành viên '.$request->add_name,
        ], 200);

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function edit(Request $request)
    {
        $infoUser = User::where('id',$request->id)->first();
        if(is_null($infoUser)){
            return response()->json([
                'error' => "Không tồn tại"
                ], 400);
        }
        session()->put('user_email_edit', $infoUser->email);
        return response()->json([
            'data' => $infoUser
        ], 200);
    }

    public function store(UserUpdateRequest $request)
    {
        $user = User::where('id',$request->edit_id)->first();
        if(is_null($user)){
            return response()->json([
                'error' => "Không tồn tại"
                ], 400);
        }
        $user->name = $request->edit_name;
        $user->email = $request->edit_email;
        $user->password = Hash::make($request->edit_password);
        $user->group_role = $request->edit_group_role;
        // $user->is_active = 0;
        // if($request->edit_status === 'on')
        // {
        //     $user->is_active = 1;
        // }
        $user->is_active = $request->edit_status;
        $user->save();
         return response()->json([
            'msg' => 'Cập nhật thành công thành viên '.$request->edit_name,
        ], 200);
    }

    private function filters(Request $request){
        $filters = [];

        if($request->has('name') && !is_null($request->name))
        {
            $filters['name'] = $request->name;
        } 

        if($request->has('email') && !is_null($request->email))
        {
            $filters['email'] = $request->email;
        } 

        if($request->has('group_role') && !is_null($request->group_role))
        {
            $filters['group_role'] = $request->group_role;
        } 

        if($request->has('status') && !is_null($request->status))
        {
            $filters['status'] = $request->status;
        } 
        return $filters;
    }

    public function loginPage()
    {
        if(Auth::check()){
            return redirect()->route('users.index');
        }
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $checkEmailExist = User::where('email', $request->email)->first();

        if(is_null($checkEmailExist)){   
            return redirect()->route('login')->with(['email' => $request->email,
                                                    'err-email' => "Email không tồn tại"]);
        }

        // if(is_null($checkEmailExist)){   
        //     return response()->json([
        //         'email' => "Email không tồn tại",
        //     ], 400);
        // }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            if($checkEmailExist->is_active === 0 || $checkEmailExist->is_delete === 1){
                return view('login',[
                    'err' => 'Tài khoản bị xóa hoặc không còn hoạt động',
                ]);
            }

            // if($checkEmailExist->is_active === 0 || $checkEmailExist->is_delete === 1){
            //     return response()->json([
            //         'error' => 'Tài khoản bị xóa hoặc không còn hoạt động',
            //     ], 400);
            // }
        
            $clientIP = request()->ip();   

            $checkEmailExist->last_login_at = Carbon::now('Asia/Ho_Chi_Minh');
            $checkEmailExist->last_login_ip = $clientIP;
            $checkEmailExist->save();

            Auth::login($checkEmailExist, $request->get('remember'));
            return redirect()->route('users.index');
            // return response()->json([
            //     'msg' => 'Đăng nhập thành công',
            // ], 200);
        }
        // return response()->json([
        //     'password' => "Mật khẩu không chính xác",
        // ], 400);
        return redirect()->route('login')->with(['email' => $request->email,
                                                'err-password' => "Mật khẩu không chính xác"]);
    }
}

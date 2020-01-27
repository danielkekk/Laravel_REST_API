<?php
 
namespace App\Http\Controllers;
 
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'c_password' => 'required|same:password',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'c_password' => 'required|same:password',
        ]);
 
        $token = $user->createToken('LaravelApp')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }
 
    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('LaravelApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'c_password' => 'required|same:password',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if(!in_array(mb_strtolower($request->email, 'UTF-8'), ['danielkekk@gmail.com'])) {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
        $token = $user->createToken('LaravelApp', ['*'])->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminLogin(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(!in_array(mb_strtolower($request->email, 'UTF-8'), ['danielkekk@gmail.com'])) {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('LaravelApp', ['*'])->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

    /**
     * Get a User's Details by id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailsById($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            return response()->json(['error' => ['id' => ['Given parameter is not a numeric value.']]], 400);
        }

        $user = DB::table('users')->where('id', $id)->first();
        if(empty($user)) {
            return response()->json(['error' => ['id' => ['User was not found.']]], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    /**
     * Get a User's Details by email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailsByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = DB::table('users')->where('email', $request->email)->first();
        if(empty($user)) {
            return response()->json(['error' => ['email' => ['This email was not found.']]], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    /**
     * Update a User's Details by id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'mother_name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $affected = DB::update('update users set mother_name = ? where id = ?', [$request->mother_name, $request->id]);
        if($affected === 0) {
            return response()->json(['error' => ['msg' => ['User('.$request->id.') was not updated.']]], 404);
        }

        $user = DB::table('users')->where('id', $request->id)->first();
        if(empty($user)) {
            return response()->json(['error' => ['id' => ['User was not found.']]], 404);
        }

        return response()->json(['user' => $user], 200);
    }
}
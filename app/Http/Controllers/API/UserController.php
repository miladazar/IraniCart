<?php
 
namespace App\Http\Controllers\API;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Activation;
use Validator;
 
class UserController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'family' => 'required',
            'password' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
 
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }
 
        $activation =Activation::where('code',request('code'))->first();
        if(is_null($activation)){
         $response = [
            'success' => false,
            'data' => null,
            'message' => 'The code is not valid.'
        ];
            return response()->json($response, 404);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['email'] = $activation['email'];
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;
        

        $activation->delete();

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User register successfully.'
        ];
 
        return response()->json($response, 200);
    }
 
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
 
            return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }



     /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function changepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
 
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }
 
        $activation =Activation::where('code',request('code'))->first();
        if(is_null($activation)){
         $response = [
            'success' => false,
            'data' => null,
            'message' => 'The code is not valid.'
        ];
            return response()->json($response, 404);
        }

        $success= User::where('email',$activation['email'])
           ->update(['password' => bcrypt(request('password')) ]);

             $response = [
            'success' => true,
            'data' => $success,
            'message' => 'change password successfully.'
        ];
    }



    /**
     * activelogin api
     *
     * @return \Illuminate\Http\Response
     */
    public function activelogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'type' => 'required',
        ]);
 
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 404);
        }


        $email = request('email');
        $activation=[
           'email'  => request('email'),
           'type'   => request('type'),
           'status' => false,
           'code'   => $this->quickRandom(),
        ];
        $success=Activation::create($activation);


        /* send email 
         *
         this is send email 
        */
        return response()->json( [
            'success' => true,
            'data' => $success,
            'message' => 'Activation Code sent successfully.'], 200);
      
    }


     /**
     * activelogin api
     *
     * @return \Illuminate\Http\Response
     */  
    private  function quickRandom($length = 6)
    {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


use Socialite;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'facebook_id' => $data['facebook_id'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }



    protected $redirectPath = '/';

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }



    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = \Laravel\Socialite\Facades\Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user);

        $this->loginUser($authUser);

        $redirect = $this->redirectAfterLogin();
        return redirect(url($redirect));
    }



    /**
     * Log the user in our application and set a personal key
     * -- personal key is used in our application to send messeges throught socket.io
     * @param User $authUser
     */
    private function loginUser(User $authUser) {

        $this->contBlocat($authUser);

        \Illuminate\Support\Facades\Auth::login($authUser, true);

        (new User())->setPersonalKey($authUser);

        return $authUser;
    }

    /**
     * Redirectionam utilizatorul dupa ce s-a logat
     * @return mixed
     */
    private function redirectAfterLogin() {
        return (null !== (Session::get('last_link'))) ? Session::get('last_link') : route('index_path') ;
    }



    /**
     * Locked Account
     * @param User $user
     */
    private function contBlocat(User $authUser) {

        if ( $authUser->status == 0 ) {
            $titlu = 'Locked account';
            $mesaj = 'Your account is locked ['.$authUser->username.'].';
            return view('errors.error',compact('titlu','mesaj'));
        }

        return $authUser;
    }



    /**
     * Create username from email and random number and return
     *
     * @param $email
     * @return username
     *
     */
    private function CreateUsername($email)
    {
        $emailparts = explode('@', $email);
        $lookfor = @substr($emailparts[0], 0, 27);
        $found = User::where('username', $lookfor)->first();
        if ($found) {
            return $found->username . round(rand(0, 999));
        } else {
            return $lookfor;
        }
    }



    /**
     *
     * Save avatar in our website and return the filename from storage
     *
     * @param $avatarLink
     * @return avatar filename which will pe stored in public/dbp/avatars/{fbid}.jpg
     */
    private function saveAvatar($avatarLink,$fid) {
        if ( copy($avatarLink,APP_AVATARS_PATH.'/'.$fid.'.jpg') ) {
            return $fid.'.jpg';
        }
        return null;
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($facebookUser)
    {
        $authUser = User::where('facebook_id', $facebookUser->id)->first();

        if ($authUser){
            return $authUser;
        }

        $newUser = User::create([
            'name' => $facebookUser->name,
            'username' => $this->CreateUsername($facebookUser->email),
            'email' => $facebookUser->email,
            'facebook_id' => $facebookUser->id,
            'avatar' => $this->saveAvatar($facebookUser->avatar,$facebookUser->id),
            'gender' => $facebookUser->user['gender'],
            'status' => '10',
        ]);

        return $newUser;
    }




    public function LogOut() {
        \Illuminate\Support\Facades\Auth::logout();
        return redirect()->route('index_path');
    }


}

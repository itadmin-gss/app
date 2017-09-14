<?php



class RemindersController extends Controller
{



    /**

     * Display the password reminder view.

     *

     * @return Response

     */

    public function getRemind()
    {

        return view('forgot_password');
    }



    /**

     * Handle a POST request to remind a user of their password.

     *

     * @return Response

     */

    public function postRemind()
    {



             $from_email = config('app.admin_email');

        switch ($response = Password::remind(Input::only('email'), function ($message) use ($from_email) {



            $message->subject('Password Reminder! for email')

            ->from($from_email, 'GSS');
        })) {
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));



            case Password::REMINDER_SENT:
                return Redirect::back()->with('status', Lang::get($response));
        }
    }



    /**

     * Display the password reset view for the given token.

     *

     * @param  string  $token

     * @return Response

     */

    public function getReset($token = null)
    {

        if (is_null($token)) {
            abort(404);
        }



        return view('password_reset')->with('token', $token);
    }



    /**

     * Handle a POST request to reset a user's password.

     *

     * @return Response

     */

    public function postReset()
    {

        $credentials = Input::only(

            'email',
            'password',
            'password_confirmation',
            'token'
        );

        $data = Input::only(

            'email',
            'password'
        );

        $response = Password::reset($credentials, function ($user, $password) {


            $user->password = Hash::make($password);



            $user->save();
        });

        $reset_id = ResetPassword::savePassword($data);

        switch ($response) {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));



            case Password::PASSWORD_RESET:
                return redirect('/');
        }
    }
}

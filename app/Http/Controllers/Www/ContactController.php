<?php namespace App\Http\Controllers\Www;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Department;
use App\Http\Requests\Www\ContactRequest;
use Mail;

class ContactController extends Controller {

    public function __construct()
    {

    }

    public function contact(ContactRequest $request)
    {
        $department = Department::findOrFail($request->input('department'));

        $recaptcha = new \ReCaptcha\ReCaptcha(\Config::get('services.recaptcha.secret'));
        $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
        if ($resp->isSuccess()) {
            $name = $request->input('name');
            $email = $request->input('email');
            $data = $request->only(['name', 'email', 'phone', 'message']);
            $data['locale'] = \Locales::getNamespace();

            Mail::send([\Locales::getNamespace() . '.emails.contact', \Locales::getNamespace() . '.emails.contact-text'], compact('data'), function ($m) use ($department, $name, $email) {
                $m->from($email, $name);
                $m->sender($email, $name);
                $m->replyTo($email, $name);
                $m->to($department->email, $department->name);
                $m->subject(trans(\Locales::getNamespace() . '/forms.contactSubject'));
            });

            return response()->json([
                'success' => trans(\Locales::getNamespace() . '/forms.contactSuccess'),
                'reset' => true,
                'resetRecaptcha' => true,
                'resetMultiselect' => [
                    'input-department' => ['refresh'],
                ],
            ]);
        } else {
            // $errors = $resp->getErrorCodes();
            return response()->json([
                'errors' => [trans(\Locales::getNamespace() . '/forms.contactError')],
                'resetRecaptcha' => true,
                'resetMultiselect' => [
                    'input-department' => ['refresh'],
                ],
            ]);
        }
    }

}

<?php namespace App\Http\Controllers\Www;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subscriber;
use App\Http\Requests\Www\SubscribeRequest;

class SubscribeController extends Controller {

    public function __construct()
    {

    }

    public function subscribe(SubscribeRequest $request, Subscriber $subscriber)
    {
        $category = $subscriber->select('id')->from($subscriber->getTable())->where('slug', \Locales::getCurrent())->first();

        $subscriber = $subscriber->firstOrNew([
            'email' => $request->input('subscribe_email'),
            'parent' => $category->id,
        ]);

        $subscriber->is_active = 1;
        $subscriber->parent = $category->id;
        $subscriber->save();

        return response()->json([
            'success' => trans(\Locales::getNamespace() . '/forms.subscribeSuccess'),
            'reset' => true,
        ]);
    }

}

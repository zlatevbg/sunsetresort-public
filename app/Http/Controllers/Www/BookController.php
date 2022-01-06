<?php namespace App\Http\Controllers\Www;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Www\BookRequest;
use Carbon\Carbon;
use App\Nav;
use App\Room;
use App\View;
use App\Meal;
use App\Booking;
use App\Transaction;
use App\TransactionTest;
use App\PricePeriod;
use App\Availability;
use App\AvailabilityPeriod;
use Mail;
use Mailgun\Mailgun;

class BookController extends Controller
{

    public $prefix;

    public function __construct(Request $request)
    {
        $this->prefix = 'SRB' . date('Y') . '-';
    }

    public function piraeus(Request $request)
    {
        $message = base64_decode($request->input('eBorica'));
        $transactionCode = substr($message, 0, 2);
        $time = substr($message, 2, 14);
        $amount = substr($message, 16, 12);
        $terminalId = substr($message, 28, 8);
        $orderId = str_replace($this->prefix, '', substr($message, 36, 15));
        $responseCode = substr($message, 51, 2);
        $version = substr($message, 53, 3);
        $signature = substr($message, 56, 128);

        $fp = fopen(storage_path('app/ssl/borica-production.key'), 'r');
        $key = fread($fp, 8192);
        fclose($fp);

        $public = openssl_pkey_get_public($key);
        $verify = openssl_verify(substr($message, 0, strlen($message) - 128), $signature, $public);
        openssl_free_key($public);

        $booking = Booking::findOrFail($orderId);
        \Locales::set($booking->locale);

        if ($verify) {
            $booking->update([
                'transactionCode' => $responseCode,
                'transactionSignatureReceived' => $signature,
            ]);

            if ($responseCode == '00') {
                $availabilityPeriods = AvailabilityPeriod::select('id')->with('availability')->whereRaw('DATE(dto) >= ? AND DATE(dfrom) < ?', [Carbon::parse($booking->dfrom), Carbon::parse($booking->dto)])->orderBy('dfrom')->get()->pluck('id');
                foreach ($booking->rooms as $room => $rooms) {
                    foreach ($rooms as $r) {
                        Availability::whereIn('period_id', $availabilityPeriods)->where('room', $room)->where('view', $r['view'])->decrement('availability');
                    }
                }

                $images = [];
                array_push($images, ['filePath' => public_path('img/' . \Locales::getNamespace() . '/header-emails.jpg'), 'filename' => 'header-emails.jpg']);
                array_push($images, ['filePath' => public_path('img/' . \Locales::getNamespace() . '/logo-emails-small.png'), 'filename' => 'logo-emails-small.png']);
                array_push($images, ['filePath' => public_path('img/' . \Locales::getNamespace() . '/facebook.png'), 'filename' => 'facebook.png']);

                $html = view(\Locales::getNamespace() . '.emails.book', compact('booking'))->render();
                $text = view(\Locales::getNamespace() . '.emails.book-text', compact('booking'))->render();
                $email = \Config::get('mail.from.address');
                $name = \Config::get('mail.from.name');

                $mailgun = Mailgun::create(env('MAILGUN_SECRET'));

                $mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                    'from' => $name . ' <' . $email . '>',
                    'h:Sender' => $name . ' <' . $email . '>',
                    'to' => $booking->name . ' <' . $booking->email . '>',
                    'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                    'html' => $html,
                    'text' => $text,
                    'o:tag' => 'booking',
                    'v:id' => $booking->id,
                    'inline' => $images,
                ]);

                $mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                    'from' => $name . ' <' . $email . '>',
                    'h:Sender' => $name . ' <' . $email . '>',
                    'to' => 'Dimitar Zlatev <mitko@sunsetresort.bg>',
                    'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                    'html' => $html,
                    'text' => $text,
                    'o:tag' => 'booking-production',
                    'v:id' => $booking->id,
                    'inline' => $images,
                ]);

                /*$mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                    'from' => $name . ' <' . $email . '>',
                    'h:Sender' => $name . ' <' . $email . '>',
                    'to' => 'Irina Dzhendova <marketing@sunsetresort.bg>',
                    'bcc' => 'Dimitar Zlatev <mitko@sunsetresort.bg>',
                    'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                    'html' => $html,
                    'text' => $text,
                    'o:tag' => 'booking-production',
                    'v:id' => $booking->id,
                    'inline' => $images,
                ]);*/

                /*Mail::send([\Locales::getNamespace() . '.emails.book', \Locales::getNamespace() . '.emails.book-text'], compact('booking'), function ($m) use ($booking) {
                    $m->from($email, $name);
                    $m->sender($email, $name);
                    $m->replyTo($email, $name);
                    $m->to('mitko@sunsetresort.bg', 'Dimitar Zlatev');
                    $m->subject('Сънсет Ризорт - Резервация ' . $this->prefix . $booking->id);
                });*/

                return redirect(\Locales::route('book-confirm'));
            } else {
                $description = trans('messages.responseCodes.' . $responseCode) != 'messages.responseCodes.' . $responseCode ? trans('messages.responseCodes.' . $responseCode) : trans('messages.responseCodes.other');
                $errors = [trans(\Locales::getNamespace() . '/forms.bookError'), trans(\Locales::getNamespace() . '/forms.bookErrorCode', ['code' => $responseCode])];
                if ($description) {
                    array_push($errors, trans(\Locales::getNamespace() . '/forms.bookErrorCodeDescription', ['description' => $description]));
                }
                return redirect(\Locales::route('book', '4'))->withErrors($errors);
            }
        } else {
            return redirect(\Locales::route('book', '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorVerify'));
        }
    }

    public function confirm(Request $request)
    {
        // $booking = Booking::findOrFail(session('book-id'));
        $booking = Transaction::findOrFail(session('book-id'));

        $modelCategory = Nav::where('is_active', 1)->where('type', 'book')->where('parent', function ($query) {
            $query->select('id')->from('nav')->where('slug', \Locales::getCurrent());
        })->firstOrFail();
        $model = Nav::where('is_active', 1)->where('parent', $modelCategory->id)->where('slug', 'book-confirm')->first();

        return view(\Locales::getNamespace() . '.book.confirm', compact('model', 'booking'));
    }

    public function confirmTest(Request $request)
    {
        $booking = TransactionTest::findOrFail(session('book-test-id'));

        $modelCategory = Nav::where('is_active', 1)->where('type', 'book')->where('parent', function ($query) {
            $query->select('id')->from('nav')->where('slug', \Locales::getCurrent());
        })->firstOrFail();
        $model = Nav::where('is_active', 1)->where('parent', $modelCategory->id)->where('slug', 'book-confirm')->first();

        return view(\Locales::getNamespace() . '.book-test.confirm', compact('model', 'booking'));
    }

    public function bookSubmit(Request $request)
    {
        if (session('booking')) {
            return view(\Locales::getNamespace() . '.book.submit');
        } else {
            abort(404);
        }
    }

    public function bookSubmitTest(Request $request)
    {
        if (session('booking')) {
            return view(\Locales::getNamespace() . '.book-test.submit');
        } else {
            abort(404);
        }
    }

    public function book(BookRequest $request, $step = null)
    {
        $lastStep = 4;

        $modelCategory = Nav::where('is_active', 1)->where('type', 'book')->where('parent', function ($query) {
            $query->select('id')->from('nav')->where('slug', \Locales::getCurrent());
        })->firstOrFail();
        $model = Nav::where('is_active', 1)->where('parent', $modelCategory->id)->where('slug', 'step-' . ($step ?: $lastStep))->first();

        if ($step) {
            $view = \View::make(\Locales::getNamespace() . '.book.step' . $step, compact('step', 'model'));

            if ($step > \Session::get('max-step')) {
                \Session::put('max-step', $step);
            }

            if ($step == 1) {
                $today = Carbon::parse(date('Y-m-d H:i:s'));
                $firstDate = AvailabilityPeriod::select('dfrom')->where('dto', '>=', $today)->orderBy('dfrom')->first()->dfrom;
                if (Carbon::parse($firstDate) < $today) {
                    $firstDate = $today->format('d.m.Y');
                }

                $view = $view->with('firstDate', $firstDate);
            } elseif ($step == 2) {
                $today = Carbon::parse(date('Y-m-d H:i:s'))->format('Ymd');
                if ($request->method() == 'POST') {
                    if ($request->input('dfrom')) {
                        \Session::put('dfrom', Carbon::parse($request->input('dfrom')));
                    }

                    if ($request->input('dto')) {
                        \Session::put('dto', Carbon::parse($request->input('dto')));
                    }
                }

                if (!\Session::has('dfrom') || !\Session::has('dto')) {
                    return redirect(\Locales::route('book', '1'));
                } else {
                    \Session::put('nights', session('dfrom')->diffInDays(session('dto')));
                }

                $rooms = Room::with('images')->where('parent', function ($query) {
                    $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('order')->get();

                \Session::put('roomsArray', $rooms->keyBy('slug'));

                $views = View::select('id', 'name', 'slug')->where('parent', function ($query) {
                    $query->select('id')->from('views')->where('slug', \Locales::getCurrent());
                })->orderBy('order')->get();

                \Session::put('viewsArray', $views->keyBy('slug'));

                $meals = Meal::selectRaw('id, price_adult, price_child, slug, IF (meals.description IS NOT NULL, CONCAT(meals.name, " (", meals.description, ")"), meals.name) as name')->where('parent', function ($query) {
                    $query->select('id')->from('meals')->where('slug', \Locales::getCurrent());
                })->orderBy('order')->get();

                \Session::put('mealsArray', $meals->keyBy('slug'));

                $slugs = $rooms->keyBy('slug');

                $pricePeriods = [];
                $periods = PricePeriod::select('dfrom', 'dto', 'discount', 'id')->with('prices')->whereRaw('DATE(dto) >= ? AND DATE(dfrom) < ?', [session('dfrom')->format('Y-m-d'), session('dto')->format('Y-m-d')])->orderBy('dfrom')->get();
                $totalPeriods = count($periods) - 1;
                $periods->map(function ($period, $periodKey) use ($slugs, $totalPeriods, &$pricePeriods) {
                    $nights = 1;

                    if (!$periodKey) {
                        $dfrom = $period->dfrom; // $dfrom = session('dfrom')->format('d.m.Y');
                    } else {
                        $dfrom = $period->dfrom;
                    }

                    if ($totalPeriods > $periodKey) {
                        $dto = Carbon::parse($period->dto)->addDay(1)->timezone('Europe/Sofia')->format('d.m.Y'); // $dto = $period->dto;
                    } else {
                        $dto = Carbon::parse($period->dto)->addDay(1)->timezone('Europe/Sofia')->format('d.m.Y'); // $dto = session('dto')->format('d.m.Y');
                        // $nights = 0;
                    }

                    $prices = [];
                    $period->prices->map(function ($price, $priceKey) use ($slugs, $period, &$pricePeriods, &$prices) {
                        if (array_key_exists($price->room, $slugs->all())) {
                            $prices[$slugs[$price->room]->slug][$price->view][$price->meal] = $price->price - (($price->price * ($period->discount ?: $price->discount)) / 100);
                        }
                    });

                    $pricePeriods[] = [
                        'from' => $dfrom,
                        'to' => $dto,
                        'nights' => Carbon::parse($dfrom)->diffInDays(Carbon::parse($dto)), // + $nights,
                        'prices' => $prices,
                    ];
                });

                $index = 0;
                foreach ($pricePeriods as $key => $value) {
                    if ($key > 0) {
                        if ($value['prices'] == $pricePeriods[$index]['prices']) {
                            $pricePeriods[$index]['to'] = $value['to'];
                            $pricePeriods[$index]['nights']++;
                            unset($pricePeriods[$key]);
                        } else {
                            $index = $key;
                        }
                    }
                }

                \Session::put('pricePeriods', $pricePeriods);

                $availability = [];
                $min_stay = [];
                $availabilityPeriods = AvailabilityPeriod::select('id')->with('availability')->whereRaw('DATE(dto) >= ? AND DATE(dfrom) < ?', [session('dfrom')->format('Y-m-d'), session('dto')->format('Y-m-d')])->orderBy('dfrom')->get();
                $availabilityPeriods->map(function ($period, $periodKey) use ($slugs, &$availability, &$min_stay) { // iterate dates
                    $period->availability->map(function ($row, $k) use ($slugs, &$availability, &$min_stay) { // iterate availability
                        $slug = $row->room; // $slugs[$row->room]->slug;

                        if (!isset($availability[$slug])) {
                            $availability[$slug] = [];
                            $min_stay[$slug] = [];
                        }

                        if (!isset($availability[$slug][$row->view])) {
                            $availability[$slug][$row->view] = $row->availability;
                        } elseif ($availability[$slug][$row->view] > $row->availability) { // set the minimum availability for the period
                            $availability[$slug][$row->view] = $row->availability;
                        }

                        if (!isset($min_stay[$slug][$row->view])) {
                            $min_stay[$slug][$row->view] = $row->min_stay;
                        } elseif ($min_stay[$slug][$row->view] < $row->min_stay) { // set the maximum minStay for the period
                            $min_stay[$slug][$row->view] = $row->min_stay;
                        }
                    });
                });

                $rooms->transform(function ($room, $roomKey) use ($availability, $min_stay, $views) {
                    $total = 0;
                    if (isset($availability[$room->slug])) {
                        $total = array_sum($availability[$room->slug]);
                    }

                    $minStay = 0;
                    if (isset($min_stay[$room->slug])) {
                        $minStay = min($min_stay[$room->slug]);
                    }

                    $rooms = [];
                    for ($i = 1; $i <= $total; $i++) {
                        $rooms[$i] = [
                            'completed' => false,
                            'view' => null,
                            'meal' => null,
                            'guests' => 0,
                            'adults' => 0,
                            'children' => 0,
                            'infants' => 0,
                            'price' => 0,
                        ];
                    }

                    return [
                        'id' => $room->id,
                        'name' => $room->name,
                        'slug' => $room->slug,
                        'area' => $room->area,
                        'capacity' => $room->capacity ?: 0,
                        'adults' => $room->adults ?: 0,
                        'children' => $room->children ?: 0,
                        'infants' => $room->infants ?: 0,
                        'content' => $room->content,
                        'availability' => $total,
                        'minStay' => $minStay,
                        'views' => $views->map(function ($view, $viewKey) use ($room, $availability, $min_stay) {
                            return [
                                'id' => $view->id,
                                'name' => $view->name,
                                'slug' => $view->slug,
                                'availability' => $availability[$room->slug][$view->slug] ?? 0,
                                'min_stay' => $min_stay[$room->slug][$view->slug] ?? 0,
                            ];
                        })->keyBy('slug')->all(),
                        'images' => $room->images,
                        'counter' => 0,
                        'rooms' => $rooms,
                    ];
                });

                $view = $view->with('rooms', $rooms->keyBy('slug'))->with('meals', $meals->keyBy('slug'))->with('pricePeriods', $pricePeriods)->with('today', $today);
            } elseif ($step == 3) {
                if ($request->method() == 'POST') {
                    $grandTotal = 0;
                    $priceFirstNight = 0;
                    $nights = session('dfrom')->diffInDays(session('dto'));
                    $rooms = [];
                    foreach (session('roomsArray') as $slug => $room) {
                        if ($request->input($slug)) {
                            $rooms[$slug] = $request->input($slug);

                            foreach ($rooms[$slug] as $id => $r) {
                                $totalNights = 0;
                                // $pricePerDay = 0;
                                $priceTotal = 0;
                                $discount = 0;
                                $discountTotal = 0;
                                $discountFirstNight = 0;
                                $periods = '';
                                $pricePeriods = session('pricePeriods');
                                $periodFrom = $pricePeriods[0]['from'];
                                $periodTo = end($pricePeriods)['to'];
                                $periodPrice = $pricePeriods[0]['prices'][$slug][$r['view']][$r['meal']];
                                $priceFirstNight += $periodPrice;
                                $samePeriod = true;

                                foreach ($pricePeriods as $period) {
                                    if ($period['prices'][$slug][$r['view']][$r['meal']] != $periodPrice) {
                                        $samePeriod = false;
                                        break;
                                    }

                                    $totalNights += $period['nights'];
                                }

                                if ($samePeriod) {
                                    $periods .= $periodFrom . ' - ' . $periodTo . ' (' . $totalNights . ' ' . trans(\Locales::getNamespace() . '/js.nights') . ') x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . $periodPrice . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($periodPrice / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                    $priceTotal += $periodPrice * $totalNights;
                                } else {
                                    $totalNights = 0;
                                    foreach ($pricePeriods as $period) {
                                        if (count($pricePeriods) > 1) {
                                            $periods .= $period['from'] . ' - ' . $period['to'] . ' (' . $period['nights'] . ' ' . trans(\Locales::getNamespace() . '/js.nights') . ') x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . $period['prices'][$slug][$r['view']][$r['meal']] . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($period['prices'][$slug][$r['view']][$r['meal']] / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        }

                                        $totalNights += $period['nights'];
                                        $priceTotal += $period['prices'][$slug][$r['view']][$r['meal']] * $period['nights'];
                                    }
                                }

                                if ($slug != 'one-bed-economy' && $slug != 'two-bed-economy') {
                                    $from = Carbon::parse($periodFrom);
                                    $to = Carbon::parse($periodTo);
                                    $today = Carbon::parse(date('Y-m-d H:i:s'))->format('Ymd');

                                    if ($today <= '20220131') {
                                        $discount = ($priceTotal * 0.25); // 25% early booking
                                        $discountFirstNight = ($priceFirstNight * 0.25); // 25% early booking
                                        $discountTotal += $discount;
                                        $priceTotal -= $discount;
                                        $priceFirstNight -= $discountFirstNight;
                                        $periods .= trans(\Locales::getNamespace() . '/js.discount25') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                    } elseif ($today <= '20220331') {
                                        $discount = ($priceTotal * 0.20); // 20% early booking
                                        $discountFirstNight = ($priceFirstNight * 0.20); // 20% early booking
                                        $discountTotal += $discount;
                                        $priceTotal -= $discount;
                                        $priceFirstNight -= $discountFirstNight;
                                        $periods .= trans(\Locales::getNamespace() . '/js.discount20') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                    }

                                    if ($totalNights == 5 || $totalNights == 6) {
                                        if ($from->year == '2021') {
                                            // $priceFirstNight -= ($periodPrice * 0.05);
                                            $discount = ($priceTotal * 0.05);
                                            $discountFirstNight = ($priceFirstNight * 0.05);
                                            $discountTotal += $discount;
                                            $priceTotal -= $discount;
                                            $priceFirstNight -= $discountFirstNight;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount5') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        } elseif ($to->format('md') <= '0630' || $from->format('md') >= '0901') {
                                            $discount = ($priceTotal * 0.05);
                                            $discountFirstNight = ($priceFirstNight * 0.05);
                                            $discountTotal += $discount;
                                            $priceTotal -= $discount;
                                            $priceFirstNight -= $discountFirstNight;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount5') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        }
                                    } elseif ($totalNights >= 7) {
                                        if ($from->year == '2021') {
                                            // $priceFirstNight -= ($periodPrice * 0.1);
                                            $discount = ($priceTotal * 0.1);
                                            $discountFirstNight = ($priceFirstNight * 0.1);
                                            $discountTotal += $discount;
                                            $priceTotal -= $discount;
                                            $priceFirstNight -= $discountFirstNight;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount10') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        } elseif ($to->format('md') <= '0630' || $from->format('md') >= '0901') {
                                            $discount = ($priceTotal * 0.1);
                                            $discountFirstNight = ($priceFirstNight * 0.1);
                                            $discountTotal += $discount;
                                            $priceTotal -= $discount;
                                            $priceFirstNight -= $discountFirstNight;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount10') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        }
                                    }
                                }

                                // $priceTotal = $priceTotal - $discountTotal;
                                // $pricePerDay = $priceTotal / $totalNights;

                                if ($r['adults'] && session('mealsArray')[$r['meal']]->price_adult) {
                                    // $pricePerDay += ($r['adults'] * session('mealsArray')[$r['meal']]->price_adult);
                                    $priceTotal += ($totalNights * $r['adults'] * session('mealsArray')[$r['meal']]->price_adult);
                                    $periods .= trans(\Locales::getNamespace() . '/js.priceAdult') . ': ' . $r['adults'] . ' x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . session('mealsArray')[$r['meal']]->price_adult . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round(session('mealsArray')[$r['meal']]->price_adult / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                }

                                if ($r['children'] && session('mealsArray')[$r['meal']]->price_child) {
                                    // $pricePerDay += ($r['children'] * session('mealsArray')[$r['meal']]->price_child);
                                    $priceTotal += ($totalNights * $r['children'] * session('mealsArray')[$r['meal']]->price_child);
                                    $periods .= trans(\Locales::getNamespace() . '/js.priceChild') . ': ' . $r['children'] . ' x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . session('mealsArray')[$r['meal']]->price_child . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round(session('mealsArray')[$r['meal']]->price_child / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                }

                                $rooms[$slug][$id]['priceTotal'] = $priceTotal;
                                $rooms[$slug][$id]['periods'] = $periods;
                                $grandTotal += $priceTotal;
                            }
                        }
                    }

                    \Session::put('grandTotal', $grandTotal);
                    \Session::put('priceFirstNight', $priceFirstNight);
                    \Session::put('rooms', $rooms);
                }

                if (!\Session::has('rooms')) {
                    return redirect(\Locales::route('book', '2'));
                }
            } elseif ($step == 4) {
                if ($request->method() == 'POST') {
                    if ($request->input('name')) {
                        \Session::put('name', $request->input('name'));
                    }

                    if ($request->input('email')) {
                        \Session::put('email', $request->input('email'));
                    }

                    if ($request->input('phone')) {
                        \Session::put('phone', $request->input('phone'));
                    }

                    if ($request->input('message')) {
                        \Session::put('message', $request->input('message'));
                    }

                    if ($request->input('invoice')) {
                        \Session::put('invoice', $request->input('invoice'));
                    }

                    if ($request->input('company')) {
                        \Session::put('company', $request->input('company'));
                    }

                    if ($request->input('country')) {
                        \Session::put('country', $request->input('country'));
                    }

                    if ($request->input('eik')) {
                        \Session::put('eik', $request->input('eik'));
                    }

                    if ($request->input('vat')) {
                        \Session::put('vat', $request->input('vat'));
                    }

                    if ($request->input('address')) {
                        \Session::put('address', $request->input('address'));
                    }

                    if ($request->input('city')) {
                        \Session::put('city', $request->input('city'));
                    }

                    if ($request->input('mol')) {
                        \Session::put('mol', $request->input('mol'));
                    }
                }

                if (!\Session::has('name') && !\Session::has('email') && !\Session::has('phone')) {
                    return redirect(\Locales::route('book', '3'));
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                $sections = $view->renderSections();
                return response()->json([
                    'modal' => $sections['content'],
                ]);
            } else {
                return $view->with('formClass', 'defaultForm');
            }
        } else {
            $recaptcha = new \ReCaptcha\ReCaptcha(\Config::get('services.recaptcha.secret'));
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            // $errors = $resp->getErrorCodes();
            if ($resp->isSuccess()) {
                \Session::forget('booking');
                $nights = session('dfrom')->diffInDays(session('dto'));
                $price = session('grandTotal');
                $price_per_day = session('priceFirstNight'); // $price / $nights;
                $TRTYPE = 1;
                $MERCH_GMT = '+0' . (Carbon::now()->timezone('Europe/Sofia')->offset / (60 * 60));
                $TIMESTAMP = Carbon::now()->format('YmdHis');
                $NONCE = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
                $AMOUNT = number_format($price_per_day, 2, '.', '');

                $transaction = Transaction::create([
                    'locale' => \Locales::getCurrent(),
                    'from' => session('dfrom')->format('Y-m-d'),
                    'to' => session('dto')->format('Y-m-d'),
                    'nights' => $nights,
                    'rooms' => session('rooms'),
                    'roomsArray' => session('roomsArray'),
                    'viewsArray' => session('viewsArray'),
                    'mealsArray' => session('mealsArray'),
                    'price' => $price,
                    'name' => session('name'),
                    'email' => session('email'),
                    'phone' => session('phone'),
                    'company' => session('company'),
                    'country' => session('country'),
                    'eik' => session('eik'),
                    'vat' => session('vat'),
                    'address' => session('address'),
                    'city' => session('city'),
                    'mol' => session('mol'),
                    'message' => session('message'),
                    'amount' => $price_per_day,
                    'type' => $TRTYPE,
                    'gmt' => $MERCH_GMT,
                    'merchant_timestamp' => $TIMESTAMP,
                    'merchant_nonce' => $NONCE,
                ]);

                if ($transaction->id) {
                    \Session::put('book-id', $transaction->id);

                    $BACKREF = \Locales::route('postbank');
                    $LANG = \Locales::getCurrent() == 'bg' ? 'BG' : 'EN';
                    $CURRENCY = 'BGN';
                    $DESC = trans(\Locales::getNamespace() . '/messages.bookingDescription', ['from' => session('dfrom')->format('d.m.Y'), 'to' => session('dto')->format('d.m.Y')]);
                    $TERMINAL = 'V6200049';
                    $MERCH_NAME = 'Sunset Resort Management EOOD';
                    $MERCH_URL = 'https://www.sunsetresort.bg/';
                    $MERCHANT = '9200200084';
                    $EMAIL = 'mitko@sunsetresort.bg';
                    $ORDER = str_pad($transaction->id, 6, '0', STR_PAD_LEFT);
                    $ORDER_ID = $ORDER . '@' . substr($this->prefix, 0, -1);
                    $COUNTRY = 'BG';
                    $ADDENDUM = 'AD,TD';

                    $data = (mb_strlen($TERMINAL)) . $TERMINAL . (mb_strlen($TRTYPE)) . $TRTYPE . (mb_strlen($AMOUNT)) . $AMOUNT . (mb_strlen($CURRENCY)) . $CURRENCY . (mb_strlen($ORDER)) . $ORDER . (mb_strlen($MERCHANT)) . $MERCHANT . (mb_strlen($TIMESTAMP)) . $TIMESTAMP . (mb_strlen($NONCE)) . $NONCE;

                    $fp = fopen(storage_path('app/ssl/production-2020.key'), 'r');
                    $key = fread($fp, 8192);
                    fclose($fp);

                    $signature = null;
                    $private = openssl_pkey_get_private($key);
                    openssl_sign($data, $signature, $private, OPENSSL_ALGO_SHA256);
                    openssl_free_key($private);

                    $P_SIGN = strtoupper(bin2hex($signature));

                    $transaction->order = $ORDER;
                    $transaction->description = $DESC;
                    $transaction->merchant_signature = $P_SIGN;
                    $transaction->save();

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'submit' => 'borica-form',
                            'fields' => [
                                'BACKREF' => $BACKREF,
                                'LANG' => $LANG,
                                'AMOUNT' => $AMOUNT,
                                'CURRENCY' => $CURRENCY,
                                'DESC' => $DESC,
                                'TERMINAL' => $TERMINAL,
                                'MERCH_NAME' => $MERCH_NAME,
                                'MERCH_URL' => $MERCH_URL,
                                'MERCHANT' => $MERCHANT,
                                'EMAIL' => $EMAIL,
                                'TRTYPE' => $TRTYPE,
                                'ORDER' => $ORDER,
                                'AD.CUST_BOR_ORDER_ID' => $ORDER_ID,
                                'COUNTRY' => $COUNTRY,
                                'TIMESTAMP' => $TIMESTAMP,
                                'MERCH_GMT' => $MERCH_GMT,
                                'NONCE' => $NONCE,
                                'ADDENDUM' => $ADDENDUM,
                                'P_SIGN' => $P_SIGN,
                            ],
                        ]);
                    } else {
                        \Session::put('booking.BACKREF', $BACKREF);
                        \Session::put('booking.LANG', $LANG);
                        \Session::put('booking.AMOUNT', $AMOUNT);
                        \Session::put('booking.CURRENCY', $CURRENCY);
                        \Session::put('booking.DESC', $DESC);
                        \Session::put('booking.TERMINAL', $TERMINAL);
                        \Session::put('booking.MERCH_NAME', $MERCH_NAME);
                        \Session::put('booking.MERCH_URL', $MERCH_URL);
                        \Session::put('booking.MERCHANT', $MERCHANT);
                        \Session::put('booking.EMAIL', $EMAIL);
                        \Session::put('booking.TRTYPE', $TRTYPE);
                        \Session::put('booking.ORDER', $ORDER);
                        \Session::put('booking.AD.CUST_BOR_ORDER_ID', $ORDER_ID);
                        \Session::put('booking.COUNTRY', $COUNTRY);
                        \Session::put('booking.TIMESTAMP', $TIMESTAMP);
                        \Session::put('booking.MERCH_GMT', $MERCH_GMT);
                        \Session::put('booking.NONCE', $NONCE);
                        \Session::put('booking.ADDENDUM', $ADDENDUM);
                        \Session::put('booking.P_SIGN', $P_SIGN);
                        return redirect(\Locales::route('book-submit'));
                    }
                } else {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'errors' => [trans(\Locales::getNamespace() . '/forms.bookErrorSave')],
                            'resetRecaptcha' => true,
                        ]);
                    } else {
                        return redirect(\Locales::route('book', '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorSave'));
                    }
                }

                /*$transactionType = 10;
                $transactionDate = date('YmdHis');
                $transactionTerminal = '92000304';
                $transactionLanguage = (in_array(\Locales::getCurrent(), ['bg', 'ru']) ? 'BG' : 'EN');
                $transactionVersion = '1.1';
                $transactionCurrency = 'BGN';
                $transactionDescription = mb_substr(trans(\Locales::getNamespace() . '/messages.bookingDescription', ['from' => session('dfrom')->format('d.m.Y'), 'to' => session('dto')->format('d.m.Y')]), 0, 125);

                $booking = Booking::create([
                    'locale' => \Locales::getCurrent(),
                    'from' => session('dfrom')->format('Y-m-d'),
                    'to' => session('dto')->format('Y-m-d'),
                    'nights' => $nights,
                    'rooms' => session('rooms'),
                    'roomsArray' => session('roomsArray'),
                    'viewsArray' => session('viewsArray'),
                    'mealsArray' => session('mealsArray'),
                    'price' => $price,
                    'name' => session('name'),
                    'email' => session('email'),
                    'phone' => session('phone'),
                    'company' => session('company'),
                    'country' => session('country'),
                    'eik' => session('eik'),
                    'vat' => session('vat'),
                    'address' => session('address'),
                    'city' => session('city'),
                    'mol' => session('mol'),
                    'message' => session('message'),
                    'transactionType' => $transactionType,
                    'transactionDate' => $transactionDate,
                    'transactionAmount' => str_pad((($price / $nights) * 100), 12, '0', STR_PAD_LEFT),
                    'transactionTerminal' => $transactionTerminal,
                    'transactionDescription' => $transactionDescription,
                    'transactionLanguage' => $transactionLanguage,
                    'transactionVersion' => $transactionVersion,
                    'transactionCurrency' => $transactionCurrency,
                ]);

                if ($booking->id) {
                    \Session::put('book-id', $booking->id);

                    $message = $transactionType;
                    $message .= $transactionDate;
                    $message .= str_pad((($price / $nights) * 100), 12, '0', STR_PAD_LEFT);
                    $message .= $transactionTerminal;
                    $message .= str_pad(substr($this->prefix . $booking->id, 0, 15), 15);
                    $message .= str_pad(mb_convert_encoding($transactionDescription, 'windows-1251', 'utf-8'), 125);
                    $message .= $transactionLanguage;
                    $message .= $transactionVersion;
                    $message .= $transactionCurrency;

                    $fp = fopen(storage_path('app/ssl/production.key'), 'r');
                    $key = fread($fp, 8192);
                    fclose($fp);

                    $signature = null;
                    $private = openssl_pkey_get_private($key);
                    openssl_sign($message, $signature, $private);
                    openssl_free_key($private);

                    $message .= $signature;

                    $booking->transactionSignatureSent = $signature;
                    $booking->save();

                    $url = 'https://gate.borica.bg/boreps/registerTransaction?eBorica=' . urlencode(base64_encode($message));

                    $email = \Config::get('mail.from.address');
                    $name = \Config::get('mail.from.name');

                    $mailgun = Mailgun::create(env('MAILGUN_SECRET'));

                    $mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                        'from' => $name . ' <' . $email . '>',
                        'h:Sender' => $name . ' <' . $email . '>',
                        'to' => 'Dimitar Zlatev <mitko@sunsetresort.bg>',
                        'subject' => 'Sunset Resort Online Booking #' . $booking->id,
                        'html' => 'Transaction started',
                        'text' => 'Transaction started',
                        'o:tag' => 'online-transaction',
                        'v:id' => $booking->id,
                    ]);

                    $redirect = redirect($url);
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['redirect' => $redirect->getTargetUrl()]);
                    } else {
                        return $redirect;
                    }
                } else {
                    if ($request->ajax() || $request->wantsJson()) {
                        $view = \View::make(\Locales::getNamespace() . '.book.step' . $lastStep, compact('step', 'model'));
                        $sections = $view->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorSave'))->renderSections();
                        return response()->json([
                            'modal' => $sections['content'],
                        ]);
                    } else {
                        return redirect(\Locales::route('book', '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorSave'));
                    }
                }*/
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    $view = \View::make(\Locales::getNamespace() . '.book.step' . $lastStep, compact('step', 'model'));
                    $sections = $view->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorCaptcha'))->renderSections();
                    return response()->json([
                        'modal' => $sections['content'],
                    ]);
                } else {
                    return redirect(\Locales::route('book', '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorCaptcha'));
                }
            }
        }
    }

    public function bookTest(BookRequest $request, $step = null)
    {
        $lastStep = 4;

        $modelCategory = Nav::where('is_active', 1)->where('type', 'book')->where('parent', function ($query) {
            $query->select('id')->from('nav')->where('slug', \Locales::getCurrent());
        })->firstOrFail();
        $model = Nav::where('is_active', 1)->where('parent', $modelCategory->id)->where('slug', 'step-' . ($step ?: $lastStep))->first();

        if ($step) {
            $view = \View::make(\Locales::getNamespace() . '.book-test.step' . $step, compact('step', 'model'));

            if ($step > \Session::get('max-step')) {
                \Session::put('max-step', $step);
            }

            if ($step == 1) {
                $today = Carbon::parse(date('Y-m-d H:i:s'));
                $firstDate = AvailabilityPeriod::select('dfrom')->where('dto', '>=', $today)->orderBy('dfrom')->first()->dfrom;
                if (Carbon::parse($firstDate) < $today) {
                    $firstDate = $today->format('d.m.Y');
                }

                $view = $view->with('firstDate', $firstDate);
            } elseif ($step == 2) {
                if ($request->method() == 'POST') {
                    if ($request->input('dfrom')) {
                        \Session::put('dfrom', Carbon::parse($request->input('dfrom')));
                    }

                    if ($request->input('dto')) {
                        \Session::put('dto', Carbon::parse($request->input('dto')));
                    }
                }

                if (!\Session::has('dfrom') || !\Session::has('dto')) {
                    return redirect(\Locales::route('book-test', '1'));
                } else {
                    \Session::put('nights', session('dfrom')->diffInDays(session('dto')));
                }

                $rooms = Room::with('images')->where('parent', function ($query) {
                    $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
                })->where('is_active', 1)->orderBy('order')->get();

                \Session::put('roomsArray', $rooms->keyBy('slug'));

                $views = View::select('id', 'name', 'slug')->where('parent', function ($query) {
                    $query->select('id')->from('views')->where('slug', \Locales::getCurrent());
                })->orderBy('order')->get();

                \Session::put('viewsArray', $views->keyBy('slug'));

                $meals = Meal::selectRaw('id, price_adult, price_child, slug, IF (meals.description IS NOT NULL, CONCAT(meals.name, " (", meals.description, ")"), meals.name) as name')->where('parent', function ($query) {
                    $query->select('id')->from('meals')->where('slug', \Locales::getCurrent());
                })->orderBy('order')->get();

                \Session::put('mealsArray', $meals->keyBy('slug'));

                $slugs = $rooms->keyBy('slug');

                $pricePeriods = [];
                $periods = PricePeriod::select('dfrom', 'dto', 'discount', 'id')->with('prices')->whereRaw('DATE(dto) >= ? AND DATE(dfrom) < ?', [session('dfrom')->format('Y-m-d'), session('dto')->format('Y-m-d')])->orderBy('dfrom')->get();
                $totalPeriods = count($periods) - 1;
                $periods->map(function ($period, $periodKey) use ($slugs, $totalPeriods, &$pricePeriods) {
                    $nights = 1;

                    if (!$periodKey) {
                        $dfrom = $period->dfrom; // $dfrom = session('dfrom')->format('d.m.Y');
                    } else {
                        $dfrom = $period->dfrom;
                    }

                    if ($totalPeriods > $periodKey) {
                        $dto = Carbon::parse($period->dto)->addDay(1)->timezone('Europe/Sofia')->format('d.m.Y'); // $dto = $period->dto;
                    } else {
                        $dto = Carbon::parse($period->dto)->addDay(1)->timezone('Europe/Sofia')->format('d.m.Y'); // $dto = session('dto')->format('d.m.Y');
                        // $nights = 0;
                    }

                    $prices = [];
                    $period->prices->map(function ($price, $priceKey) use ($slugs, $period, &$pricePeriods, &$prices) {
                        if (array_key_exists($price->room, $slugs->all())) {
                            $prices[$slugs[$price->room]->slug][$price->view][$price->meal] = $price->price - (($price->price * ($period->discount ?: $price->discount)) / 100);
                        }
                    });

                    $pricePeriods[] = [
                        'from' => $dfrom,
                        'to' => $dto,
                        'nights' => Carbon::parse($dfrom)->diffInDays(Carbon::parse($dto)), // + $nights,
                        'prices' => $prices,
                    ];
                });

                $index = 0;
                foreach ($pricePeriods as $key => $value) {
                    if ($key > 0) {
                        if ($value['prices'] == $pricePeriods[$index]['prices']) {
                            $pricePeriods[$index]['to'] = $value['to'];
                            $pricePeriods[$index]['nights']++;
                            unset($pricePeriods[$key]);
                        } else {
                            $index = $key;
                        }
                    }
                }

                \Session::put('pricePeriods', $pricePeriods);

                $availability = [];
                $availabilityPeriods = AvailabilityPeriod::select('id')->with('availability')->whereRaw('DATE(dto) >= ? AND DATE(dfrom) < ?', [session('dfrom')->format('Y-m-d'), session('dto')->format('Y-m-d')])->orderBy('dfrom')->get();
                $availabilityPeriods->map(function ($period, $periodKey) use ($slugs, &$availability) {
                    $period->availability->map(function ($row, $k) use ($slugs, &$availability) {
                        $slug = $row->room; // $slugs[$row->room]->slug;

                        if (!isset($availability[$slug])) {
                            $availability[$slug] = [];
                        }

                        if (!isset($availability[$slug][$row->view])) {
                            $availability[$slug][$row->view] = $row->availability;
                        } elseif ($availability[$slug][$row->view] > $row->availability) {
                            $availability[$slug][$row->view] = $row->availability;
                        }
                    });
                });

                $rooms->transform(function ($room, $roomKey) use ($availability, $views) {
                    $total = 0;
                    if (isset($availability[$room->slug])) {
                        $total = array_sum($availability[$room->slug]);
                    }

                    $rooms = [];
                    for ($i = 1; $i <= $total; $i++) {
                        $rooms[$i] = [
                            'completed' => false,
                            'view' => null,
                            'meal' => null,
                            'guests' => 0,
                            'adults' => 0,
                            'children' => 0,
                            'infants' => 0,
                            'price' => 0,
                        ];
                    }

                    return [
                        'id' => $room->id,
                        'name' => $room->name,
                        'slug' => $room->slug,
                        'area' => $room->area,
                        'capacity' => $room->capacity ?: 0,
                        'adults' => $room->adults ?: 0,
                        'children' => $room->children ?: 0,
                        'infants' => $room->infants ?: 0,
                        'content' => $room->content,
                        'availability' => $total,
                        'views' => $views->map(function ($view, $viewKey) use ($room, $availability) {
                            return [
                                'id' => $view->id,
                                'name' => $view->name,
                                'slug' => $view->slug,
                                'availability' => $availability[$room->slug][$view->slug] ?? 0,
                            ];
                        })->keyBy('slug')->all(),
                        'images' => $room->images,
                        'counter' => 0,
                        'rooms' => $rooms,
                    ];
                });

                $view = $view->with('rooms', $rooms->keyBy('slug'))->with('meals', $meals->keyBy('slug'))->with('pricePeriods', $pricePeriods);
            } elseif ($step == 3) {
                if ($request->method() == 'POST') {
                    $grandTotal = 0;
                    $rooms = [];
                    foreach (session('roomsArray') as $slug => $room) {
                        if ($request->input($slug)) {
                            $rooms[$slug] = $request->input($slug);

                            foreach ($rooms[$slug] as $id => $r) {
                                $totalNights = 0;
                                // $pricePerDay = 0;
                                $priceTotal = 0;
                                $discount = 0;
                                $discountTotal = 0;
                                $periods = '';
                                $pricePeriods = session('pricePeriods');
                                $periodFrom = $pricePeriods[0]['from'];
                                $periodTo = end($pricePeriods)['to'];
                                $periodPrice = $pricePeriods[0]['prices'][$slug][$r['view']][$r['meal']];
                                $samePeriod = true;

                                foreach ($pricePeriods as $period) {
                                    if ($period['prices'][$slug][$r['view']][$r['meal']] != $periodPrice) {
                                        $samePeriod = false;
                                        break;
                                    }

                                    $totalNights += $period['nights'];
                                }

                                if ($samePeriod) {
                                    $periods .= $periodFrom . ' - ' . $periodTo . ' (' . $totalNights . ' ' . trans(\Locales::getNamespace() . '/js.nights') . ') x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . $periodPrice . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($periodPrice / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                    $priceTotal += $periodPrice * $totalNights;
                                } else {
                                    $totalNights = 0;
                                    foreach ($pricePeriods as $period) {
                                        if (count($pricePeriods) > 1) {
                                            $periods .= $period['from'] . ' - ' . $period['to'] . ' (' . $period['nights'] . ' ' . trans(\Locales::getNamespace() . '/js.nights') . ') x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . $period['prices'][$slug][$r['view']][$r['meal']] . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($period['prices'][$slug][$r['view']][$r['meal']] / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        }

                                        $totalNights += $period['nights'];
                                        $priceTotal += $period['prices'][$slug][$r['view']][$r['meal']] * $period['nights'];
                                    }
                                }

                                if ($slug != 'one-bed-economy' && $slug != 'two-bed-economy') {
                                    $from = Carbon::parse($periodFrom);
                                    $to = Carbon::parse($periodTo);
                                    $today = Carbon::parse(date('Y-m-d H:i:s'))->format('Ymd');

                                    if ($today <= '20220131') {
                                        $discount = ($priceTotal * 0.25); // 25% early booking
                                        $priceTotal -= $discount;
                                        $discountTotal += $discount;
                                        $periods .= trans(\Locales::getNamespace() . '/js.discount25') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                    } elseif ($today <= '20220331') {
                                        $discount = ($priceTotal * 0.20); // 20% early booking
                                        $priceTotal -= $discount;
                                        $discountTotal += $discount;
                                        $periods .= trans(\Locales::getNamespace() . '/js.discount20') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                    }

                                    if ($totalNights == 5 || $totalNights == 6) {
                                        if ($from->year == '2021') {
                                            $discount = ($priceTotal * 0.05);
                                            $priceTotal -= $discount;
                                            $discountTotal += $discount;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount5') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        } elseif ($to->format('md') <= '0630' || $from->format('md') >= '0901') {
                                            $discount = ($priceTotal * 0.05);
                                            $priceTotal -= $discount;
                                            $discountTotal += $discount;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount5') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        }
                                    } elseif ($totalNights >= 7) {
                                        if ($from->year == '2021') {
                                            $discount = ($priceTotal * 0.1);
                                            $priceTotal -= $discount;
                                            $discountTotal += $discount;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount10') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        } elseif ($to->format('md') <= '0630' || $from->format('md') >= '0901') {
                                            $discount = ($priceTotal * 0.1);
                                            $priceTotal -= $discount;
                                            $discountTotal += $discount;
                                            $periods .= trans(\Locales::getNamespace() . '/js.discount10') . ': ' . $discount . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round($discount / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                        }
                                    }
                                }

                                // $priceTotal = $priceTotal - $discountTotal;
                                // $pricePerDay = $priceTotal / $totalNights;

                                if ($r['adults'] && session('mealsArray')[$r['meal']]->price_adult) {
                                    // $pricePerDay += ($r['adults'] * session('mealsArray')[$r['meal']]->price_adult);
                                    $priceTotal += ($totalNights * $r['adults'] * session('mealsArray')[$r['meal']]->price_adult);
                                    $periods .= trans(\Locales::getNamespace() . '/js.priceAdult') . ': ' . $r['adults'] . ' x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . session('mealsArray')[$r['meal']]->price_adult . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round(session('mealsArray')[$r['meal']]->price_adult / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                }

                                if ($r['children'] && session('mealsArray')[$r['meal']]->price_child) {
                                    // $pricePerDay += ($r['children'] * session('mealsArray')[$r['meal']]->price_child);
                                    $priceTotal += ($totalNights * $r['children'] * session('mealsArray')[$r['meal']]->price_child);
                                    $periods .= trans(\Locales::getNamespace() . '/js.priceChild') . ': ' . $r['children'] . ' x ' . trans(\Locales::getNamespace() . '/messages.bgn-sign-before') . session('mealsArray')[$r['meal']]->price_child . trans(\Locales::getNamespace() . '/messages.bgn-sign-after') . ' (' . trans(\Locales::getNamespace() . '/messages.eur-sign-before') . round(session('mealsArray')[$r['meal']]->price_child / 1.95, 2) . trans(\Locales::getNamespace() . '/messages.eur-sign-after') . ')<br>';
                                }

                                $rooms[$slug][$id]['priceTotal'] = $priceTotal;
                                $rooms[$slug][$id]['periods'] = $periods;
                                $grandTotal += $priceTotal;
                            }
                        }
                    }

                    \Session::put('grandTotal', $grandTotal);
                    \Session::put('rooms', $rooms);
                }

                if (!\Session::has('rooms')) {
                    return redirect(\Locales::route('book-test', '2'));
                }
            } elseif ($step == 4) {
                if ($request->method() == 'POST') {
                    if ($request->input('name')) {
                        \Session::put('name', $request->input('name'));
                    }

                    if ($request->input('email')) {
                        \Session::put('email', $request->input('email'));
                    }

                    if ($request->input('phone')) {
                        \Session::put('phone', $request->input('phone'));
                    }

                    if ($request->input('message')) {
                        \Session::put('message', $request->input('message'));
                    }

                    if ($request->input('invoice')) {
                        \Session::put('invoice', $request->input('invoice'));
                    }

                    if ($request->input('company')) {
                        \Session::put('company', $request->input('company'));
                    }

                    if ($request->input('country')) {
                        \Session::put('country', $request->input('country'));
                    }

                    if ($request->input('eik')) {
                        \Session::put('eik', $request->input('eik'));
                    }

                    if ($request->input('vat')) {
                        \Session::put('vat', $request->input('vat'));
                    }

                    if ($request->input('address')) {
                        \Session::put('address', $request->input('address'));
                    }

                    if ($request->input('city')) {
                        \Session::put('city', $request->input('city'));
                    }

                    if ($request->input('mol')) {
                        \Session::put('mol', $request->input('mol'));
                    }
                }

                if (!\Session::has('name') && !\Session::has('email') && !\Session::has('phone')) {
                    return redirect(\Locales::route('book-test', '3'));
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                $sections = $view->renderSections();
                return response()->json([
                    'modal' => $sections['content'],
                ]);
            } else {
                return $view->with('formClass', 'defaultForm');
            }
        } else {
            $recaptcha = new \ReCaptcha\ReCaptcha(\Config::get('services.recaptcha.secret'));
            $resp = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
            // $errors = $resp->getErrorCodes();
            if ($resp->isSuccess()) {
                \Session::forget('booking');
                $nights = session('dfrom')->diffInDays(session('dto'));
                $price = session('grandTotal');
                $price_per_day = $price / $nights;
                $TRTYPE = 1;
                $MERCH_GMT = '+0' . (Carbon::now()->timezone('Europe/Sofia')->offset / (60 * 60));
                $TIMESTAMP = Carbon::now()->format('YmdHis');
                $NONCE = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
                $AMOUNT = number_format($price_per_day, 2, '.', '');

                $transaction = TransactionTest::create([
                    'locale' => \Locales::getCurrent(),
                    'from' => session('dfrom')->format('Y-m-d'),
                    'to' => session('dto')->format('Y-m-d'),
                    'nights' => $nights,
                    'rooms' => session('rooms'),
                    'roomsArray' => session('roomsArray'),
                    'viewsArray' => session('viewsArray'),
                    'mealsArray' => session('mealsArray'),
                    'price' => $price,
                    'name' => session('name'),
                    'email' => session('email'),
                    'phone' => session('phone'),
                    'company' => session('company'),
                    'country' => session('country'),
                    'eik' => session('eik'),
                    'vat' => session('vat'),
                    'address' => session('address'),
                    'city' => session('city'),
                    'mol' => session('mol'),
                    'message' => session('message'),
                    'amount' => $price_per_day,
                    'type' => $TRTYPE,
                    'gmt' => $MERCH_GMT,
                    'merchant_timestamp' => $TIMESTAMP,
                    'merchant_nonce' => $NONCE,
                ]);

                if ($transaction->id) {
                    \Session::put('book-test-id', $transaction->id);

                    $BACKREF = \Locales::route('postbank');
                    $LANG = \Locales::getCurrent() == 'bg' ? 'BG' : 'EN';
                    $CURRENCY = 'BGN';
                    $DESC = trans(\Locales::getNamespace() . '/messages.bookingDescription', ['from' => session('dfrom')->format('d.m.Y'), 'to' => session('dto')->format('d.m.Y')]);
                    $TERMINAL = 'V6200049';
                    $MERCH_NAME = 'Sunset Resort Management EOOD';
                    $MERCH_URL = 'https://www.sunsetresort.bg/';
                    $MERCHANT = '9200200084';
                    $EMAIL = 'mitko@sunsetresort.bg';
                    $ORDER = str_pad($transaction->id, 6, '8', STR_PAD_LEFT);
                    $ORDER_ID = $ORDER . '@TEST-' . substr($this->prefix, 0, -1);
                    $COUNTRY = 'BG';
                    $ADDENDUM = 'AD,TD';

                    $data = (mb_strlen($TERMINAL)) . $TERMINAL . (mb_strlen($TRTYPE)) . $TRTYPE . (mb_strlen($AMOUNT)) . $AMOUNT . (mb_strlen($CURRENCY)) . $CURRENCY . (mb_strlen($ORDER)) . $ORDER . (mb_strlen($MERCHANT)) . $MERCHANT . (mb_strlen($TIMESTAMP)) . $TIMESTAMP . (mb_strlen($NONCE)) . $NONCE;

                    $fp = fopen(storage_path('app/ssl/test-2020.key'), 'r');
                    $key = fread($fp, 8192);
                    fclose($fp);

                    $signature = null;
                    $private = openssl_pkey_get_private($key);
                    openssl_sign($data, $signature, $private, OPENSSL_ALGO_SHA256);
                    openssl_free_key($private);

                    $P_SIGN = strtoupper(bin2hex($signature));

                    $transaction->order = $ORDER;
                    $transaction->description = $DESC;
                    $transaction->merchant_signature = $P_SIGN;
                    $transaction->save();

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'submit' => 'borica-form',
                            'fields' => [
                                'BACKREF' => $BACKREF,
                                'LANG' => $LANG,
                                'AMOUNT' => $AMOUNT,
                                'CURRENCY' => $CURRENCY,
                                'DESC' => $DESC,
                                'TERMINAL' => $TERMINAL,
                                'MERCH_NAME' => $MERCH_NAME,
                                'MERCH_URL' => $MERCH_URL,
                                'MERCHANT' => $MERCHANT,
                                'EMAIL' => $EMAIL,
                                'TRTYPE' => $TRTYPE,
                                'ORDER' => $ORDER,
                                'AD.CUST_BOR_ORDER_ID' => $ORDER_ID,
                                'COUNTRY' => $COUNTRY,
                                'TIMESTAMP' => $TIMESTAMP,
                                'MERCH_GMT' => $MERCH_GMT,
                                'NONCE' => $NONCE,
                                'ADDENDUM' => $ADDENDUM,
                                'P_SIGN' => $P_SIGN,
                            ],
                        ]);
                    } else {
                        \Session::put('booking.BACKREF', $BACKREF);
                        \Session::put('booking.LANG', $LANG);
                        \Session::put('booking.AMOUNT', $AMOUNT);
                        \Session::put('booking.CURRENCY', $CURRENCY);
                        \Session::put('booking.DESC', $DESC);
                        \Session::put('booking.TERMINAL', $TERMINAL);
                        \Session::put('booking.MERCH_NAME', $MERCH_NAME);
                        \Session::put('booking.MERCH_URL', $MERCH_URL);
                        \Session::put('booking.MERCHANT', $MERCHANT);
                        \Session::put('booking.EMAIL', $EMAIL);
                        \Session::put('booking.TRTYPE', $TRTYPE);
                        \Session::put('booking.ORDER', $ORDER);
                        \Session::put('booking.AD.CUST_BOR_ORDER_ID', $ORDER_ID);
                        \Session::put('booking.COUNTRY', $COUNTRY);
                        \Session::put('booking.TIMESTAMP', $TIMESTAMP);
                        \Session::put('booking.MERCH_GMT', $MERCH_GMT);
                        \Session::put('booking.NONCE', $NONCE);
                        \Session::put('booking.ADDENDUM', $ADDENDUM);
                        \Session::put('booking.P_SIGN', $P_SIGN);
                        return redirect(\Locales::route('book-submit-test'));
                    }
                } else {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'errors' => [trans(\Locales::getNamespace() . '/forms.bookErrorSave')],
                            'resetRecaptcha' => true,
                        ]);
                    } else {
                        return redirect(\Locales::route('book-test', '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorSave'));
                    }
                }
            } else {
                if ($request->ajax() || $request->wantsJson()) {
                    $view = \View::make(\Locales::getNamespace() . '.book.step' . $lastStep, compact('step', 'model'));
                    $sections = $view->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorCaptcha'))->renderSections();
                    return response()->json([
                        'modal' => $sections['content'],
                    ]);
                } else {
                    return redirect(\Locales::route('book-test', '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorCaptcha'));
                }
            }
        }
    }

    public function postbank(Request $request)
    {
        $ERR_CODES = trans('messages.errCodes');

        $ACTION = $request->input('ACTION');
        $RC = $request->input('RC');
        $STATUSMSG = $request->input('STATUSMSG');
        $TERMINAL = $request->input('TERMINAL');
        $TRTYPE = $request->input('TRTYPE');
        $AMOUNT = $request->input('AMOUNT');
        $CURRENCY = $request->input('CURRENCY');
        $ORDER = $request->input('ORDER');
        $LANG = $request->input('LANG');
        $TIMESTAMP = $request->input('TIMESTAMP');
        $TRAN_DATE = $request->input('TRAN_DATE');
        $APPROVAL = $request->input('APPROVAL');
        $RRN = $request->input('RRN');
        $INT_REF = $request->input('INT_REF');
        $PARES_STATUS = $request->input('PARES_STATUS');
        $ECI = $request->input('ECI');
        $CARD = $request->input('CARD');
        $NONCE = $request->input('NONCE');
        $P_SIGN = $request->input('P_SIGN');
        $P_SIGN_BIN = hex2bin($P_SIGN);

        $test = ($ORDER[0] == 8);

        $data = ($ACTION != '' ? mb_strlen($ACTION) : '-') . $ACTION . ($RC != '' ? mb_strlen($RC) : '-') . $RC . ($APPROVAL != '' ? mb_strlen($APPROVAL) : '-') . $APPROVAL . ($TERMINAL != '' ? mb_strlen($TERMINAL) : '-') . $TERMINAL . ($TRTYPE != '' ? mb_strlen($TRTYPE) : '-') . $TRTYPE . ($AMOUNT != '' ? mb_strlen($AMOUNT) : '-') . $AMOUNT . ($CURRENCY != '' ? mb_strlen($CURRENCY) : '-') . $CURRENCY . ($ORDER != '' ? mb_strlen($ORDER) : '-') . $ORDER . ($RRN != '' ? mb_strlen($RRN) : '-') . $RRN . ($INT_REF != '' ? mb_strlen($INT_REF) : '-') . $INT_REF . ($PARES_STATUS != '' ? mb_strlen($PARES_STATUS) : '-') . $PARES_STATUS . ($ECI != '' ? mb_strlen($ECI) : '-') . $ECI . ($TIMESTAMP != '' ? mb_strlen($TIMESTAMP) : '-') . $TIMESTAMP . ($NONCE != '' ? mb_strlen($NONCE) : '-') . $NONCE;

        $fp = fopen(storage_path('app/ssl/borica-' . ($test ? 'test-2020.pem' : 'production-2020.pub')), 'r');
        $key = fread($fp, 8192);
        fclose($fp);

        $public = openssl_pkey_get_public($key);
        $result = openssl_verify($data, $P_SIGN_BIN, $public, OPENSSL_ALGO_SHA256);
        openssl_free_key($public);

        // \Log::debug($test);
        // \Log::debug($result);
        // \Log::debug($ACTION);
        // \Log::debug($RC);

        if ($result == 1) {
            if ($test) {
                $booking = TransactionTest::whereNull('rc')->findOrFail(ltrim($ORDER, '8'));
            } else {
                $booking = Transaction::whereNull('rc')->findOrFail(ltrim($ORDER, '0'));
            }

            \Locales::set($booking->locale);

            $booking->update([
                'code' => strtoupper(substr(\Uuid::generate(), -12)),
                'action' => $ACTION,
                'rc' => $RC,
                'status_msg' => $STATUSMSG,
                'approval' => $APPROVAL,
                'rrn' => $RRN,
                'int_ref' => $INT_REF,
                'pares_status' => $PARES_STATUS,
                'eci' => $ECI,
                'card' => $CARD,
                'borica_timestamp' => $TIMESTAMP,
                'borica_tran_date' => $TRAN_DATE,
                'borica_nonce' => $NONCE,
                'borica_signature' => $P_SIGN,
            ]);

            if ($RC == '00') {
                if ($ACTION == 0) {
                    $availabilityPeriods = AvailabilityPeriod::select('id')->with('availability')->whereRaw('DATE(dto) >= ? AND DATE(dfrom) < ?', [Carbon::parse($booking->dfrom), Carbon::parse($booking->dto)])->orderBy('dfrom')->get()->pluck('id');
                    foreach ($booking->rooms as $room => $rooms) {
                        foreach ($rooms as $r) {
                            Availability::whereIn('period_id', $availabilityPeriods)->where('room', $room)->where('view', $r['view'])->decrement('availability');
                        }
                    }

                    $images = [];
                    array_push($images, ['filePath' => public_path('img/' . \Locales::getNamespace() . '/header-emails.jpg'), 'filename' => 'header-emails.jpg']);
                    array_push($images, ['filePath' => public_path('img/' . \Locales::getNamespace() . '/logo-emails-small.png'), 'filename' => 'logo-emails-small.png']);
                    array_push($images, ['filePath' => public_path('img/' . \Locales::getNamespace() . '/facebook.png'), 'filename' => 'facebook.png']);

                    $html = view(\Locales::getNamespace() . '.emails.book', compact('booking'))->render();
                    $text = view(\Locales::getNamespace() . '.emails.book-text', compact('booking'))->render();
                    $email = \Config::get('mail.from.address');
                    $name = \Config::get('mail.from.name');

                    $mailgun = Mailgun::create(env('MAILGUN_SECRET'));

                    /*$mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                        'from' => $name . ' <' . $email . '>',
                        'h:Sender' => $name . ' <' . $email . '>',
                        'to' => 'Dimitar Zlatev <mitko@sunsetresort.bg>',
                        'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                        'html' => $html,
                        'text' => $text,
                        'o:tag' => 'booking',
                        'v:id' => $booking->id,
                        'inline' => $images,
                    ]);*/

                    if ($test) {
                        $mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                            'from' => $name . ' <' . $email . '>',
                            'h:Sender' => $name . ' <' . $email . '>',
                            'to' => 'Dimitar Zlatev <mitko@sunsetresort.bg>',
                            'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                            'html' => $html,
                            'text' => $text,
                            'o:tag' => 'booking',
                            'v:id' => $booking->id,
                            'inline' => $images,
                        ]);
                    } else {
                        $mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                            'from' => $name . ' <' . $email . '>',
                            'h:Sender' => $name . ' <' . $email . '>',
                            'to' => 'Irina Dzhendova <marketing@sunsetresort.bg>',
                            'bcc' => 'Dimitar Zlatev <mitko@sunsetresort.bg>',
                            'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                            'html' => $html,
                            'text' => $text,
                            'o:tag' => 'booking',
                            'v:id' => $booking->id,
                            'inline' => $images,
                        ]);
                    }

                    try {
                        $mailgun->messages()->send(env('MAILGUN_DOMAIN'), [
                            'from' => $name . ' <' . $email . '>',
                            'h:Sender' => $name . ' <' . $email . '>',
                            'to' => $booking->name . ' <' . $booking->email . '>',
                            'subject' => trans(\Locales::getNamespace() . '/newsletters.subject'),
                            'html' => $html,
                            'text' => $text,
                            'o:tag' => 'booking',
                            'v:id' => $booking->id,
                            'inline' => $images,
                        ]);
                    } catch (\Exception $e) {

                    }

                    /*Mail::send([\Locales::getNamespace() . '.emails.book', \Locales::getNamespace() . '.emails.book-text'], compact('booking'), function ($m) use ($booking) {
                        $m->from($email, $name);
                        $m->sender($email, $name);
                        $m->replyTo($email, $name);
                        $m->to('mitko@sunsetresort.bg', 'Dimitar Zlatev');
                        $m->subject('Сънсет Ризорт - Резервация ' . $this->prefix . $booking->id);
                    });*/

                    return redirect(\Locales::route('book-confirm' . ($test ? '-test' : '')));
                } else if ($ACTION == 1) {
                    return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors('ACTION - Duplicate Transaction');
                } else if ($ACTION == 2) {
                    return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors('ACTION - Declined');
                } else if ($ACTION == 3) {
                    return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors('ACTION - Unknown Error');
                }
            } elseif ($RC == '65' || $RC == '1A') {
                return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors('Soft Decline - Please try again');
                // { "threeDSRequestorChallengeInd":"04" }
            } elseif (array_key_exists($RC, $ERR_CODES)) {
                return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors($ERR_CODES[$RC]);
            } else {
                return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors('RC - Unknown Error');
            }
        } elseif ($result == 0) {
            return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors(trans(\Locales::getNamespace() . '/forms.bookErrorVerify'));
        } else {
            return redirect(\Locales::route('book' . ($test ? '-test' : ''), '4'))->withErrors(openssl_error_string());
        }
    }

}

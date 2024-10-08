<?php

namespace App\Http\Controllers;

use App\Models\Reserves;
use App\Http\Requests\StoreReserveRequest;
// use App\Http\Requests\UpdateItemRequest;
use App\Http\Libraries\ReserveFunctions;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReserveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $seach = array(
            'week' => $request->week,
            'stayTime'  => $request->stayTime ===null ? 1 : $request->stayTime,
        );
        return Inertia::render('Reserve/index', [
            'weeks' => ReserveFunctions::getWeekDates($seach['week'], $seach['stayTime']),
            'reserves' => ReserveFunctions::getAvailableDates($seach['week'], $seach['stayTime']),
            'seach' => $seach,
            ]);
    }

    public function checkAuth(Request $request)
    {
        $request->validate([
            'day' => ['required'],
            'hour' => ['required'],
            'stayTime' => ['required'],
        ]);
        $start_at = ReserveFunctions::makeReserveStart($request);
        $stay_time = $request->stayTime == null ? 1 : $request->stayTime;

        if (Auth::check()) {
            return redirect()->route('user.reserve.create', [
                    'start_at' => $start_at,
                    'stay_time' => $stay_time,
                ]);
        } else {
            return redirect()->route('login', [
                    'start_at' => $start_at,
                    'stay_time' => $stay_time,
                ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $checkResult = ReserveFunctions::checkReserve($request->start_at, $request->stay_time);
        $amount = ReserveFunctions::getAmount($request->stay_time);

        if ($checkResult) {
            return Inertia::render('User/Reserve/Create', [
                'start_at' => $request->start_at,
                'stay_time' => intval($request->stay_time),
                'amount' => $amount,

                'stripe_public_key' => config('stripe.public_key'),
            ]);
        } else {
            $msg = "選択いただいた時間では予約ができませんでした";
            return Inertia::render('User/Reserve/CheckError', [
                'message' => $msg,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReserveRequest $request)
    {
        $request->validate([
            'start_at' => 'required|string',
            'stay_time' => 'required|numeric',
            'amount' => 'required|numeric',
            // 'stripeToken' => 'required|array',
        ]);

        list($result, $msg) = ReserveFunctions::createReserve($request);

        if ($result) {
            return to_route('user.reserve.list')
            ->with([
                'message' => '予約が完了しました',
                'status' => 'success'
            ]);
        } else {
            return Inertia::render('User/Reserve/CheckError', [
                'message' => $msg,
            ]);
        }
    }

    public function adminList(Request $request)
    {
        $seach = array(
            'user_id' => $request->user_id,
            'user_name' => $request->user_name,
            'type' => $request->type == null ? 0 : $request->type,
        );

        $reserves = Reserves::with('user')
                            ->seachAdminList($seach['user_id'], $seach['user_name'], $seach['type'])
                            ->orderBy('start_at')
                            ->paginate(20)->withQueryString();

        return Inertia::render('Administer/Reserve/List',[
            'reserves' => $reserves,
            'seach' => $seach,
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function userList()
    {
        $reserves = Reserves::where('users_id', Auth::id())->where('deleted_at', null)
                    ->orderBy('start_at')->get();
        return Inertia::render('User/Reserve/List',[
            'reserves' => $reserves
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return Inertia::render('Items/Edit',[
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        // dd($item->name, $request->name);
        $item->name = $request->name;
        $item->memo = $request->memo;
        $item->price = $request->price;
        $item->is_selling = $request->is_selling;
        $item->save();

        return to_route('items.index')
        ->with([
            'message' => '更新しました',
            'status' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return to_route('items.index')
        ->with([
            'message' => '削除しました',
            'status' => 'danger'
        ]);
    }

    public function cansel(Request $request)
    {
        $request->validate([
            'reserve_id' => 'required|numeric',
        ]);
        $result = ReserveFunctions::cansel($request->reserve_id);

        if ($result) {
            return to_route('user.reserve.list')->with([
                'message' => '削除しました',
                'status' => 'success'
            ]);
        }
        return to_route('user.reserve.list')->with([
            'message' => '削除に失敗しました',
            'status' => 'danger'
        ]);
    }

}

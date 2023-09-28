<?php

namespace App\Http\Controllers;

// use App\Models\Item;
use App\Http\Requests\StoreReserveRequest;
// use App\Http\Requests\UpdateItemRequest;
use App\Http\Libraries\ReserveFunctions;
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
        // return Inertia::render('Reserve/index', [
        //     'items' => Item::select('id', 'name', 'price', 'is_selling')->get(),
        // ]);
        $seach = array(
            'week' => $request->week,
            'stayTime'  => $request->stayTime ===null ? 0 : $request->stayTime,
        );
        return Inertia::render('Reserve/index', [
            'weeks' => ReserveFunctions::getWeekDates($seach['week'], $seach['stayTime']),
            'reserves' => ReserveFunctions::getAvailableDates($seach['week'], $seach['stayTime']),
            'seach' => $seach,
            ]);
    }

    public function checkAuth(StoreReserveRequest $request)
    {
        $start_at = ReserveFunctions::makeReserveStart($request);

        if (Auth::check()) {
            return redirect()->route('reserves.create', ['start_at' => $start_at]);
        } else {
            return redirect()->route('login', ['start_at' => $start_at]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Items/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReserveRequest $request)
    {
        $result = ReserveFunctions::createReserve($request);

        if ($result) {
            return true;
        } else {
            return 1212;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return Inertia::render('Items/Show',[
            'item' => $item
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

    public function test(Request $request)
    {
        dd($request->all());
        return to_route('items.show',['item'=>2]);
    }

}

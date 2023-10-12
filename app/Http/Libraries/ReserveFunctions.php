<?php
namespace App\Http\Libraries;

use App\Models\Reserves;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class ReserveFunctions
{
   /**
    * Invokes the common functions
    *
    * @param string $data
    * @return array
    */
    public static function getWeekDates($week = null)
    {
        $dates = [];
        // 週の開始日（月曜日）を取得
        if ($week !== null) {
            $start = Carbon::now()->addWeeks($week)->startOfWeek();
        } else {
            $start = Carbon::now()->startOfWeek();
        }

        // 1週間の日付をループで取得
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $dates[] = $date->format('n/d'); // 「月/日」の形式で日付を追加
        }

        return $dates;
    }

    public static function getAvailableDates($week = null, $stayTime = null)
    {
        $now = Carbon::now();
        $target = $now;
        if ($week !== null) {
            $target = $now->copy()->addWeeks($week);
        }

        // 24時間以上取れないと想定して、各日１日前後を取得する
        $start = $target->copy()->startOfWeek()->subDay();
        $endOfWeek = $target->copy()->endOfWeek()->addDay();
        $availableDates = [];

        $reservations = Reserves::whereBetween('start_at', [$start, $endOfWeek])
            ->orWhereBetween('finish_at', [$start, $endOfWeek])
            ->get();

        // 30分ごとにインクリメント
        // for ($date = $start; $date->lte($endOfWeek); $date->addMinutes(30))
        // 1時間ごと
        for ($date = $start; $date->lte($endOfWeek); $date->addHour()) {
            $existingReservation = $reservations->filter(function ($reservation) use ($date, $stayTime) {
                // ->subHours($time)をstart_atにつけて、2時間予約時の時間分を確保する
                $start_at  = Carbon::createFromFormat('Y-m-j H:i:s', $reservation->start_at);
                if ($stayTime !== null) {
                    $start_at  =  $start_at->subHours($stayTime)->addMinute();
                }
                $finish_at = Carbon::createFromFormat('Y-m-j H:i:s', $reservation->finish_at);
                return $date->between($start_at, $finish_at->subMinute());
            })->count();

            // 重複していない、かつ現在時刻より未来の場合
            if (!$existingReservation && $now < $date) {
                $availableDates[$date->format('n/d G')] = true;
            } else {
                $availableDates[$date->format('n/d G')] = false;
            }
        }
        return $availableDates;
    }

    public static function makeReserveStart($request)
    {
        $day = $request->day;
        $hour = $request->hour;

        // 年を追加 (この例では現在の年を使用)
        $year = Carbon::now()->year; // 現在の年を取得

        $dateTimeString = "$year/$day $hour:00";
        return $dateTimeString;

        // 日付と時間の文字列をパースして Carbon オブジェクトを作成
        // return Carbon::createFromFormat('Y/n/j H:i', $dateTimeString);
    }

    public static function checkReserve($start_at = null, $stay_time = null)
    {
        if ($start_at === null || $stay_time === null
            || !preg_match( '/^[0-9]+$/', $stay_time)) {
            return false;
        }

        $now = Carbon::now();
        $start_at = Carbon::createFromFormat('Y/n/j H:i', $start_at);
        $stay_time = intval($stay_time);
        $finish_at = $start_at->copy()->addHours($stay_time)->subMinute();

        if ($now > $start_at || $start_at->minute !== 0
            || $stay_time >= 6) {
            return false;
        }

        // 予約時間の重複を確認
        $overlappingReservations = Reserves::where(function ($query) use ($start_at, $finish_at) {
            $query->whereBetween('start_at', [$start_at, $finish_at])
                ->orWhereBetween('finish_at', [$start_at, $finish_at])
                // start_at が start前で finish_at が finish後
                ->orWhere(function ($query) use ($start_at, $finish_at) {
                    $query->where('start_at', '<', $start_at)->where('finish_at', '>', $finish_at);
                });
        })->get();
        if ($overlappingReservations->count() > 0) {
            return false;
        }

        return true;
    }

    public static function createReserve($request)
    {
        $result = self::checkReserve($request->start_at, $request->stay_time);
        if ($result === false) {
            return array(false, "予約時間が確保できませんでした。決済は行われていませんので、ご安心ください。");
        }

        // $startTime = self::makeReserveStart($request);
        // $finishTime = $startTime->copy()->addHours(1);
        $start_at = Carbon::createFromFormat('Y/n/j H:i', $request->start_at);
        $stay_time = intval($request->stay_time);
        $finish_at = $start_at->copy()->addHours($stay_time);

        // Stripeのシークレットキーを設定
        Stripe::setApiKey(config('stripe.secret_key'));

        DB::beginTransaction();

        try {

            $reserve = Reserves::create([
                'users_id' => Auth::id(),
                'status' => 1,
                'start_at' => $start_at,
                'finish_at' => $finish_at,
                'charge_id' => 'temp',
                'amount' => $request->amount,
            ]);

            $charge = \Stripe\Charge::create([
                "amount" => $request->amount,  // 金額をセント単位で指定
                "currency" => "jpy",
                "source" => $request->stripeToken['id'],  // 取得したトークンをここで使用
                "description" => "テスト決済"
            ]);

            $reserve->charge_id = $charge->id;
            $reserve->save();

            DB::commit();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return array(false, "決済でエラーが発生しました。時間を置いてから再度ご確認をお願いいたします。");
        }
        return array(true, null);
    }
    public static function cansel($reserve_id)
    {
        $reserve = Reserves::find($reserve_id);
        $now = Carbon::now();
        if ($reserve->start_at >= $now || !is_null($reserve->deleted_at)) {
            return false;
        }
        Stripe::setApiKey(config('stripe.secret_key'));

        DB::beginTransaction();

        try {
            $refund = \Stripe\Refund::create([
                'charge' => $reserve->refund_id,
                // 'amount' => 500, Charge時の通貨単位
            ]);

            dd($refund);

            $reserve->deleted_at = $now;
            $reserve->save();

            DB::commit();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return array(false, "決済でエラーが発生しました。時間を置いてから再度ご確認をお願いいたします。");
        }

    }

    public static function getAmount($stay_time = 0)
    {
        return intval($stay_time) * config('price.hour');
    }
}
<?php
namespace App\Http\Libraries;

use App\Models\Reserves;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReserveFunctions
{
   /**
    * Invokes the common functions
    *
    * @param string $data
    * @return array
    */
    public static function getWeekDates()
    {
        $dates = [];
        $start = Carbon::now()->startOfWeek(); // 週の開始日（月曜日）を取得

        // 1週間の日付をループで取得
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $dates[] = $date->format('n/d'); // 「月/日」の形式で日付を追加
        }

        return $dates;
    }

    public static function getAvailableDates()
    {
        $now = Carbon::now();
        // 24時間以上取れないと想定して、各日１日前後を取得する
        $start = $now->copy()->startOfWeek()->subDay();
        $endOfWeek = $now->copy()->endOfWeek()->addDay();
        $availableDates = [];

        $reservations = Reserves::whereBetween('start_at', [$start, $endOfWeek])
            ->orWhereBetween('finish_at', [$start, $endOfWeek])
            ->get();

        // 30分ごとにインクリメント
        // for ($date = $start; $date->lte($endOfWeek); $date->addMinutes(30))
        // 1時間ごと
        for ($date = $start; $date->lte($endOfWeek); $date->addHour()) {
            $existingReservation = $reservations->filter(function ($reservation) use ($date) {
                // ->subHours($time)をstart_atにつけて、2時間予約時の時間分を確保する
                $finish_at = Carbon::createFromFormat('Y-m-j H:i:s', $reservation->finish_at);
                return $date->between($reservation->start_at, $finish_at->subMinute());
            })->count();

            if (!$existingReservation && $now < $date) {
                $availableDates[$date->format('n/d G')] = true;
            } else {
                $availableDates[$date->format('n/d G')] = false;
            }
        }
        return $availableDates;
    }

    public static function createReserve($request)
    {
        $day = $request->day;
        $hour = str_replace('時', '', $request->hour);

        // 年を追加 (この例では現在の年を使用)
        $year = Carbon::now()->year; // 現在の年を取得

        $dateTimeString = "$year/$day $hour:00";

        // 日付と時間の文字列をパースして Carbon オブジェクトを作成
        $startTime = Carbon::createFromFormat('Y/n/j H:i', $dateTimeString);
        $finishTime = $startTime->copy()->addHours(1);

        DB::beginTransaction();

        try {
            Reserves::create([
                'users_id' => 2,
                'status' => 1,
                'start_at' => $startTime,
                'finish_at' => $finishTime,
            ]);
            DB::commit();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
        return true;
    }
}
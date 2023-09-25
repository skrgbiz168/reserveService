<?php
namespace App\Http\Libraries;

use Carbon\Carbon;

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
}
<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DateFilterController extends Controller
{
    public function index()
    {
        $startDate = '2024-11-01';
        $endDate = '2024-11-30';
        $StartMonth = '2024-11';
        $EndMonth = '';


        //day wise
        $Day = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1394)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");
        // ->get();


        $Day1 = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1394)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");
        // ->get();
        $DayWise = $Day->union($Day1)->get();
        // Month wise
        $Monthmessages = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1394)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");
        // ->pluck('send_msg', 'message_date');

        $Monthmessages1 = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1394)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");
        // ->pluck('send_msg', 'message_date');

        $MonthWise = DB::query()
            ->fromSub($Monthmessages->union($Monthmessages1), 'combined')
            ->selectRaw('message_date, SUM(send_msg) as total_send_msg')
            ->groupBy('message_date')
            ->pluck('total_send_msg', 'message_date');


        $months = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);
        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        $result = [];
        foreach ($months as $month) {
            $result[$month] = $MonthWise[$month] ?? 0;
        }

        //end


        //day wise incomplete lead
        $Dayincomplete = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1526)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");

        $Dayincomplete1 = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1526)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");

        $incompleteday = $Dayincomplete->union($Dayincomplete1)->get();


        // Month wise incomplete lead
        $Monthincomplete = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1526)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");


        $Monthincomplete1 = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1526)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");

        $incompletemonth = DB::query()
            ->fromSub($Monthincomplete->union($Monthincomplete1), 'combined')
            ->selectRaw('message_date, SUM(send_msg) as total_send_msg')
            ->groupBy('message_date')
            ->pluck('total_send_msg', 'message_date');

        $incomplete = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);
        while ($start <= $end) {
            $incomplete[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        $incompleteresult = [];
        foreach ($incomplete as $month) {
            $incompleteresult[$month] = $incompletemonth[$month] ?? 0;
        }
        //dd($incompleteresult);
        //end

        // Sample Book  start
        // $samplebook = DB::table('tbl_whatsapp_message_send_log_unregister as a')
        //     ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
        //     ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
        //     ->where('a.whatsapp_template_id', 1526)
        //     ->whereColumn('b.created_at', '>=', 'a.created_date')
        //     ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
        //     ->when($endDate, function ($query, $endDate) {
        //         $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
        //     })
        //     // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
        //     ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')")
        //     ->get();
        // dd($samplebook);
        $samplebook = DB::table('tbl_whatsapp_message_send_log_unregister as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1526)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d'),a.customer_phn_number");
        // ->get();
        $samplebook1 = DB::table('tbl_whatsapp_message_send_log as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1526)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d'),a.customer_phn_number");
        // ->get();

        $sample_book = $samplebook->union($samplebook1)->get();

        $samplebookdata = [];
        foreach ($sample_book as $sample) {
            $samplebookdata[$sample->message_date][] = $sample->customer_phn_number;
        }
        // dd($samplebookdata);

        $samplebookMonth = DB::table('tbl_whatsapp_message_send_log_unregister as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1526)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?",  $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m'),a.customer_phn_number");
        // ->get();

        $samplebookMonth1 = DB::table('tbl_whatsapp_message_send_log as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1526)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?",  $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m'),a.customer_phn_number");
        // ->get();
        $sample_book_month = $samplebookMonth->union($samplebookMonth1)->get();
        $samplebookdata_month = [];
        foreach ($sample_book_month as $sample) {
            $samplebookdata_month[$sample->message_date][] = $sample->customer_phn_number;
        }
        // dd($samplebookdata_month);


        // $samplebookMonth = DB::table('tbl_whatsapp_message_send_log_unregister as a')
        //     ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
        //     ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
        //     ->where('a.whatsapp_template_id', 1526)
        //     ->whereColumn('b.created_at', '>=', 'a.created_date')
        //     ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
        //     ->when($EndMonth, function ($query, $EndMonth) {
        //         $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
        //     })
        //     // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
        //     ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')")
        //     ->pluck('send_msg', 'message_date');
        // $samplemonths = [];
        // $start = new DateTime($StartMonth);
        // $end = new DateTime($EndMonth);
        // while ($start <= $end) {
        //     $samplemonths[] = $start->format('Y-m');
        //     $start->modify('+1 month');
        // }

        // $sampleresult = [];
        // foreach ($samplemonths as $month) {
        //     $sampleresult[$month] = $samplebookMonth[$month] ?? 0;
        // }
        // dd($samplebookMonth);
        //end sample book


        //day wise lead incomplete message reminder
        $Dayincomplete_msg_reminder = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1527)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");

        $Dayincomplete1_msg_reminder = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1527)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");

        $incompleteday_msg_reminder = $Dayincomplete_msg_reminder->union($Dayincomplete1_msg_reminder)->get();
        // dd($incompleteday_msg_reminder);

        // Month wise lead incomplete message reminder
        $Monthincomplete_msg_reminder = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1527)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");


        $Monthincomplete1_msg_reminder = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1527)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");

        $incompletemonth_msg_reminder = DB::query()
            ->fromSub($Monthincomplete_msg_reminder->union($Monthincomplete1_msg_reminder), 'combined')
            ->selectRaw('message_date, SUM(send_msg) as total_send_msg')
            ->groupBy('message_date')
            ->pluck('total_send_msg', 'message_date');

        $incomplete_msg_reminder = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);
        while ($start <= $end) {
            $incomplete_msg_reminder[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        $incompleteresult_msg_reminder = [];
        foreach ($incomplete_msg_reminder as $month) {
            $incompleteresult_msg_reminder[$month] = $incompletemonth_msg_reminder[$month] ?? 0;
        }
        //end of incomplete_msg_reminder

        //start of sample book_reminder unique *********
        $samplebook_reminder = DB::table('tbl_whatsapp_message_send_log_unregister as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1527)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d'),a.customer_phn_number");
        // ->get();

        $samplebook1_reminder = DB::table('tbl_whatsapp_message_send_log as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1527)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d'),a.customer_phn_number");
        // ->get();

        $sample_bookdata_reminder = $samplebook_reminder->union($samplebook1_reminder)->get();
        $samplebookdata_reminder = [];
        foreach ($sample_bookdata_reminder as $sample) {
            $samplebookdata_reminder[$sample->message_date][] = $sample->customer_phn_number;
        }
        // dd($samplebookdata_reminder);
        $differences = [];
        foreach ($samplebookdata_reminder as $date => $reminderNumber) {
            if (isset($samplebookdata[$date])) {
                $differences[$date] = array_diff($reminderNumber, $samplebookdata[$date]);
            } else {
                $differences[$date] = $reminderNumber;
            }
        }
        $differences = array_filter($differences, function ($numbers) {
            return !empty($numbers);
        });
        // dd($differences);


        $samplebook_reminder_Month = DB::table('tbl_whatsapp_message_send_log_unregister as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1527)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?",  $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m'),a.customer_phn_number");
        // ->get();

        $samplebook_reminder_Month1 = DB::table('tbl_whatsapp_message_send_log as a')
            ->join('zadmin_loyalty_egg.ak_sample_product_order as b', 'a.customer_phn_number', '=', 'b.customer_phone')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date,a.customer_phn_number")
            // ->select('a.customer_phn_number')
            ->where('a.whatsapp_template_id', 1527)
            ->whereColumn('b.created_at', '>=', 'a.created_date')
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?",  $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m'),a.customer_phn_number");
        // ->get();

        $sample_bookreminder_month = $samplebook_reminder_Month->union($samplebook_reminder_Month1)->get();
        $samplebookdata_month_reminder = [];
        foreach ($sample_bookreminder_month as $sample) {
            $samplebookdata_month_reminder[$sample->message_date][] = $sample->customer_phn_number;
        }
        // dd($samplebookdata_month_reminder);
        $differences_month = [];
        foreach ($samplebookdata_month_reminder as $month => $reminderNumbers) {
            if (isset($samplebookdata_month[$month])) {
                $differences_month[$month] = array_diff($reminderNumbers, $samplebookdata_month[$month]);
            } else {
                $differences_month[$month] = $reminderNumbers;
            }
        }
        $differences_month = array_filter($differences_month, function ($numbers) {
            return !empty($numbers);
        });
        // dd($differences_month);
        //end sample book reminder


        //Non servisable Customer day wise *******

        $Nonservisable_Day = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1525)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");
        // ->get();

        $Nonservisable1_Day = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1525)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= ?", $startDate)
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", $endDate)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')");
        // ->get();
        // dd($Nonservisable_Day);
        $Day_NonServisable = $Nonservisable_Day->union($Nonservisable1_Day)->get();


        // Non servisable Customer Month wise 
        $Nonservisable_Month = DB::table('tbl_whatsapp_message_send_log')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1525)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");
        // ->pluck('send_msg', 'message_date');

        $Nonservisabl1e_Month = DB::table('tbl_whatsapp_message_send_log_unregister')
            ->selectRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') as message_date, COUNT(*) as send_msg")
            ->where('whatsapp_template_id', 1525)
            ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth);
            })
            // ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m') <= ?", $EndMonth)
            ->groupByRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m')");
        // ->pluck('send_msg', 'message_date');

        $nonServisablemonth = DB::query()
            ->fromSub($Nonservisable_Month->union($Nonservisabl1e_Month), 'combined')
            ->selectRaw('message_date, SUM(send_msg) as total_send_msg')
            ->groupBy('message_date')
            ->pluck('total_send_msg', 'message_date');


        $months = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);
        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        $Nonservisable_result = [];
        foreach ($months as $month) {
            $Nonservisable_result[$month] = $nonServisablemonth[$month] ?? 0;
        }
        // dd($result);
        //end

        // Day 0 (Delivery Of Sample Day) ********
        $get_data_btl_day = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            ->selectRaw("DATE_FORMAT(day_0, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_0, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_0, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_0, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        // Generate a full date range
        $all_dates = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $all_dates[$currentDate->format('Y-m-d')] = 0; // Default count is 0
            $currentDate->modify('+1 day');
        }

        // Merge query results with the date range
        foreach ($get_data_btl_day as $date => $data) {
            $all_dates[$date] = $data->total_count;
        }

        $get_data_btl_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            ->selectRaw("DATE_FORMAT(day_0, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_0,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_0,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_0, '%Y-%m')"))
            ->get();


        // Day 0 ( Ordered Joining Bonus day wise ) ********
        $get_data_btl_bonus0 = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            ->selectRaw("DATE_FORMAT(day_0, '%Y-%m-%d') as date, COUNT(*) as total_count")
            // ->select('journey_id', 'customer_phone')
            ->whereRaw("DATE_FORMAT(day_0, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_0, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereColumn('utilized_date', '<', 'day_3')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_0, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $joining_Bonus_0 = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $joining_Bonus_0[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }

        // Merge query results with the date range
        foreach ($get_data_btl_bonus0 as $date => $data) {
            $joining_Bonus_0[$date] = $data->total_count;
        }

        // Day 0 ( Ordered Joining Bonus Month Wise) ********
        $get_data_btl_bonus0_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            ->selectRaw("DATE_FORMAT(day_0, '%Y-%m') as date, COUNT(*) as total_count")
            // ->select('journey_id', 'customer_phone')
            ->whereRaw("DATE_FORMAT(day_0,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_0,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereColumn('utilized_date', '<', 'day_3')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_0, '%Y-%m')"))
            ->get();

        $incomplete_joining = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);
        while ($start <= $end) {
            $incomplete_joining[] = $start->format('Y-m');
            $start->modify('+1 month');
        }

        $bonus_0_month = [];
        foreach ($incomplete_joining as $month) {
            $bonus_0_month[$month] = $get_data_btl_bonus0_month[$month] ?? 0;
        }
        // dd($bonus_0_month);

        // Day 0 ( Recharge Day Wise) ********
        $get_data_btl_day0_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_0, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_0, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_0, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereNull('day_3')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_0, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day0_recharege = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day0_recharege[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }

        // Merge query results with the date range
        foreach ($get_data_btl_day0_recharge as $date => $data) {
            $day0_recharege[$date] = $data->total_count;
        }

        // Day 0 ( Recharge Month) ********
        $get_data_btl_day0_month_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_0, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_0,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_0,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereNull('day_3')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_0, '%Y-%m')"))
            ->get()
            ->keyBy('date');


        $month_recharge = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $month_recharge[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $recharge_0_month = [];
        foreach ($month_recharge as $month) {
            $recharge_0_month[$month] = $get_data_btl_day0_month_recharge[$month]->total_count ?? 0;
        }


        // Day 3 (Sent Message Day Wise) ********
        $get_data_btl_day3 = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_3, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_3, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_3, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_3')
                    ->orWhereNull('utilized_date');
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_3, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day3_sentMessage = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day3_sentMessage[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_btl_day3 as $date => $data) {
            $day3_sentMessage[$date] = $data->total_count;
        }

        // Day 3 (Sent Message Month Wise) ********

        $get_data_btl_day3_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_3, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_3,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_3,'%Y-%m') <= ?", $EndMonth);
            })
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_3')
                    ->orWhereNull('utilized_date');
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_3, '%Y-%m')"))
            ->get()
            ->keyBy('date');
        $day3_sent_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day3_sent_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day3_month_sent = [];
        foreach ($day3_sent_month as $month) {
            $day3_month_sent[$month] = $get_data_btl_day3_month[$month]->total_count ?? 0;
        }


        // Day 3 ( Order Joining Bonus day Wise) ********
        $get_data_day3_bonus = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_3, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_3, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_3, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereColumn('utilized_date', '<', 'day_6')
            ->whereColumn('utilized_date', '>', 'day_3')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_3, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day3_bonus = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day3_bonus[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_day3_bonus as $date => $data) {
            $day3_bonus[$date] = $data->total_count;
        };

        // Day 3 ( Order Joining Bonus Month Wise) ********

        $data_day3_bonus_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_3, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_3,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_3,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereColumn('utilized_date', '<', 'day_6')
            ->whereColumn('utilized_date', '>', 'day_3')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_3, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day3_bonus_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day3_bonus_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day3_month_bonus = [];
        foreach ($day3_bonus_month as $month) {
            $day3_month_bonus[$month] = $data_day3_bonus_month[$month]->total_count ?? 0;
        }

        // Day 3 ( Recharge Day Wise) ********
        $get_data_day3_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_3, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_3, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_3, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereNull('day_6')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_3, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day3_recharge = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day3_recharge[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_day3_recharge as $date => $data) {
            $day3_recharge[$date] = $data->total_count;
        };

        // Day 3 ( Recharge month Wise) ********
        $get_day3_recharge_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_3, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_3,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_3,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereNull('day_6')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_3, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $monthrecharge = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $monthrecharge[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $recharge_day3_month = [];
        foreach ($monthrecharge as $month) {
            $recharge_day3_month[$month] = $get_day3_recharge_month[$month]->total_count ?? 0;
        }

        // Day 6 (Sent Message Day Wise) ********
        $get_day6_sent = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_6, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_6, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_6, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_6, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day6_sentMessage = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day6_sentMessage[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day6_sent as $date => $data) {
            $day6_sentMessage[$date] = $data->total_count;
        }


        // Day 6 (Sent Message Month Wise) ********
        $get_day6_sent_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_6, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_6,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_6,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_6, '%Y-%m')"))
            ->get()
            ->keyBy('date');
        $day6_sent_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day6_sent_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day6_month_sent = [];
        foreach ($day6_sent_month as $month) {
            $day6_month_sent[$month] = $get_day6_sent_month[$month]->total_count ?? 0;
        }

        // Day 6 ( Order Joining Bonus day Wise) ********
        $get_day6_bonus = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_6, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_6, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_6, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereColumn('utilized_date', '<', 'day_9')
            ->whereColumn('utilized_date', '>', 'day_6')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_6, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day6_bonus = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day6_bonus[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day6_bonus as $date => $data) {
            $day6_bonus[$date] = $data->total_count;
        }


        // Day 6 ( Order Joining Bonus Month Wise) ********
        $get_day6_bonus_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_6, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_6,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_6,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereColumn('utilized_date', '<', 'day_9')
            ->whereColumn('utilized_date', '>', 'day_6')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_6, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day6_bonus_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day6_bonus_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day6_month_bonus = [];
        foreach ($day6_bonus_month as $month) {
            $day6_month_bonus[$month] = $get_day6_bonus_month[$month]->total_count ?? 0;
        }

        // Day 6 (Recharge Day Wise) ********
        $get_data_day6_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_6, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_6, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_6, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereNull('day_9')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_6, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day6_recharge = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day6_recharge[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_day6_recharge as $date => $data) {
            $day6_recharge[$date] = $data->total_count;
        };

        // Day 6 (Recharge Month Wise) ********
        $get_day6_recharge_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_6, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_6,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_6,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereNull('day_9')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_6, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day6_recharge_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day6_recharge_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day6_month_recharge = [];
        foreach ($day6_recharge_month as $month) {
            $day6_month_recharge[$month] = $get_day6_recharge_month[$month]->total_count ?? 0;
        }

        // Day 9 (Sent Message Day Wise) ********
        $get_day9_sent = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_9')
                    ->orWhereNull('utilized_date');
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day9_sentMessage = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day9_sentMessage[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day9_sent as $date => $data) {
            $day9_sentMessage[$date] = $data->total_count;
        }

        // Day 9 (Sent Message Month Wise) ********
        $get_day9_sent_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_9,'%Y-%m') <= ?", $EndMonth);
            })
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_9')
                    ->orWhereNull('utilized_date');
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m')"))
            ->get()
            ->keyBy('date');
        $day9_sent_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day9_sent_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day9_month_sent = [];
        foreach ($day9_sent_month as $month) {
            $day9_month_sent[$month] = $get_day9_sent_month[$month]->total_count ?? 0;
        }

        // Day 9 ( Order Joining Bonus day Wise) ********
        $get_day9_bonus = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereColumn('utilized_date', '<', 'day_12')
            ->whereColumn('utilized_date', '>', 'day_9')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day9_bonus = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day9_bonus[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day9_bonus as $date => $data) {
            $day9_bonus[$date] = $data->total_count;
        }

        // Day 9 ( Order Joining Bonus Month Wise) ********
        $get_day9_bonus_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_9,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereColumn('utilized_date', '<', 'day_12')
            ->whereColumn('utilized_date', '>', 'day_9')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day9_bonus_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day9_bonus_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day9_month_bonus = [];
        foreach ($day9_bonus_month as $month) {
            $day9_month_bonus[$month] = $get_day9_bonus_month[$month]->total_count ?? 0;
        }

        // Day 9 (Recharge Day Wise) ********
        $get_data_day9_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereNull('day_12')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_9')
                    ->orWhereNull('utilized_date');
            })
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day9_recharge = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day9_recharge[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_day9_recharge as $date => $data) {
            $day9_recharge[$date] = $data->total_count;
        };

        // Day 9 (Recharge month Wise) ********
        $get_data_day9_recharge_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_9,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereNull('day_12')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_9')
                    ->orWhereNull('utilized_date');
            })
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day9_recharge_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day9_recharge_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day9_month_recharge = [];
        foreach ($day9_recharge_month as $month) {
            $day9_month_recharge[$month] = $get_data_day9_recharge_month[$month]->total_count ?? 0;
        }

        // Day 12 (Sent Message Day Wise) ********
        $get_day12_sent = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_12, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_12, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_12, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_12, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');


        $day12_sentMessage = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day12_sentMessage[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day12_sent as $date => $data) {
            $day12_sentMessage[$date] = $data->total_count;
        }

        // Day 12 (Sent Message Month Wise) ********
        $get_day12_sent_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_12, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_12,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_12,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_12, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day12_sent_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day12_sent_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day12_month_sent = [];
        foreach ($day12_sent_month as $month) {
            $day12_month_sent[$month] = $get_day12_sent_month[$month]->total_count ?? 0;
        }

        // Day 12 ( Order Joining Bonus day Wise) ********
        $get_day12_bonus = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_12, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_12, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_12, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereColumn('utilized_date', '<', 'day_15')
            ->whereColumn('utilized_date', '>', 'day_12')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_12, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day12_bonus = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day12_bonus[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day12_bonus as $date => $data) {
            $day12_bonus[$date] = $data->total_count;
        }

        // Day 12 ( Order Joining Bonus Month Wise) ********
        $get_day12_bonus_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_12, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_12,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_6,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereColumn('utilized_date', '<', 'day_15')
            ->whereColumn('utilized_date', '>', 'day_12')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_12, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day12_bonus_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day12_bonus_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day12_month_bonus = [];
        foreach ($day12_bonus_month as $month) {
            $day12_month_bonus[$month] = $get_day12_bonus_month[$month]->total_count ?? 0;
        }


        // Day 12 (Recharge Day Wise) ********
        $get_data_day12_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_12, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_12, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_12, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereNull('day_15')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_12, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day12_recharge = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day12_recharge[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_day12_recharge as $date => $data) {
            $day12_recharge[$date] = $data->total_count;
        };

        // Day 12 (Recharge month Wise) ********
        $get_data_day12_recharge_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_12, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_12,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_12,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereNull('day_15')
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_12, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day12_recharge_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day12_recharge_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day12_month_recharge = [];
        foreach ($day12_recharge_month as $month) {
            $day12_month_recharge[$month] = $get_data_day12_recharge_month[$month]->total_count ?? 0;
        }

        // Day 15 ( Sent Message  Day Wise) ********
        $get_day15_sent = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_15') // utilized_date > day_15
                    ->orWhereNull('utilized_date'); // OR utilized_date IS NULL
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');


        $day15_sentMessage = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day15_sentMessage[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day15_sent as $date => $data) {
            $day15_sentMessage[$date] = $data->total_count;
        }

        // Day 15 (Sent Message Month Wise) ********
        $get_day15_sent_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_15,'%Y-%m') <= ?", $EndMonth);
            })
            ->where(function ($query) {
                $query->whereColumn('utilized_date', '>', 'day_15')
                    ->orWhereNull('utilized_date');
            })
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day15_sent_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day15_sent_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day15_month_sent = [];
        foreach ($day15_sent_month as $month) {
            $day15_month_sent[$month] = $get_day15_sent_month[$month]->total_count ?? 0;
        }

        // Day 15 ( Order Joining Bonus day Wise) ********

        $get_day15_bonus = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereColumn('utilized_date', '>', 'day_15')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day15_bonus = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day15_bonus[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day15_bonus as $date => $data) {
            $day15_bonus[$date] = $data->total_count;
        }

        // Day 15 ( Order Joining Bonus Month Wise) ********
        $get_day15_bonus_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_15,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereColumn('utilized_date', '>', 'day_15')
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day15_bonus_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day15_bonus_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day15_month_bonus = [];
        foreach ($day15_bonus_month as $month) {
            $day15_month_bonus[$month] = $get_day15_bonus_month[$month]->total_count ?? 0;
        }

        // Day 15 (Recharge Day Wise) ********
        $get_data_day15_recharge = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('first_recharge_date', '>', DB::raw('day_15'))
            ->where('recharge', '1')
            ->where('journey', '0')
            ->where(function ($query) {
                $query->where('utilized_date', '>', DB::raw('day_15'))
                    ->orWhereNull('utilized_date');
            })
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day15_recharge = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day15_recharge[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_data_day15_recharge as $date => $data) {
            $day15_recharge[$date] = $data->total_count;
        };

        // Day 15 (Recharge month Wise) ********
        $get_data_day15_recharge_month = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_15,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('first_recharge_date', '>', DB::raw('day_15'))
            ->where('recharge', '1')
            ->where('journey', '0')
            ->where(function ($query) {
                $query->where('utilized_date', '>', DB::raw('day_15'))
                    ->orWhereNull('utilized_date');
            })
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day15_recharge_month = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day15_recharge_month[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day15_month_recharge = [];
        foreach ($day15_recharge_month as $month) {
            $day15_month_recharge[$month] = $get_data_day15_recharge_month[$month]->total_count ?? 0;
        }


        // Day 9 (Sent Message Day Wise Did't Recharge) ********
        $day9_sent_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('utilized_date', '<', DB::raw('day_9'))
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day9_sentMessage_no = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day9_sentMessage_no[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($day9_sent_no as $date => $data) {
            $day9_sentMessage_no[$date] = $data->total_count;
        }

        // Day 9 (Sent Message Month Wise Did't Recharge) ********
        $get_day9_sent_month_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_9,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('utilized_date', '<', DB::raw('day_9'))
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m')"))
            ->get()
            ->keyBy('date');
        $day9_sent_month_no = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day9_sent_month_no[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day9_month_sent_no = [];
        foreach ($day9_sent_month_no as $month) {
            $day9_month_sent_no[$month] = $get_day9_sent_month_no[$month]->total_count ?? 0;
        }


        // Day 9 ( Order Joining Bonus day Wise Did't Recharge) ********
        $get_day9_bonus_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('utilized_date', '<', DB::raw('day_12'))
            ->where('utilized_date', '>', DB::raw('day_9'))
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day9_bonus_no = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day9_bonus_no[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day9_bonus_no as $date => $data) {
            $day9_bonus_no[$date] = $data->total_count;
        }

        // Day 9 ( Order Joining Bonus Month Wise Did't Recharge) ********
        $get_day9_bonus_month_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_9,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('utilized_date', '<', DB::raw('day_12'))
            ->where('utilized_date', '>', DB::raw('day_9'))
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day9_bonus_month_no = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day9_bonus_month_no[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day9_month_bonus_no = [];
        foreach ($day9_bonus_month_no as $month) {
            $day9_month_bonus_no[$month] = $get_day9_bonus_month_no[$month]->total_count ?? 0;
        }


        // Day 9 (Recharge Day Wise) ********
        $get_day9_recharge_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_9, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->whereNull('day_12')
            ->where('utilized_date', '<', DB::raw('day_9'))
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day9_recharge_no = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day9_recharge_no[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day9_recharge_no as $date => $data) {
            $day9_recharge_no[$date] = $data->total_count;
        };


        // Day 9 (Recharge month Wise) ********
        $get_day9_recharge_month_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_9, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_9,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_9,'%Y-%m') <= ?", $EndMonth);
            })
            ->whereNull('day_12')
            ->where('utilized_date', '<', DB::raw('day_9'))
            ->where('recharge', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_9, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day9_recharge_month_no = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day9_recharge_month_no[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day9_month_recharge_no = [];
        foreach ($day9_recharge_month_no as $month) {
            $day9_month_recharge_no[$month] = $get_day9_recharge_month_no[$month]->total_count ?? 0;
        }

        // Day 15 ( Sent Message  Day Wise Did't Recharge ) ********
        $get_day15_sent_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('utilized_date', '<', DB::raw('day_15'))
            ->whereNotNull('utilized_date')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day15_sentMessage_no = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day15_sentMessage_no[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day15_sent_no as $date => $data) {
            $day15_sentMessage_no[$date] = $data->total_count;
        }

        // Day 15 (Sent Message Month Wise) ********
        $get_day15_sent_month_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone') 
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_15,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('utilized_date', '<', DB::raw('day_15'))
            ->whereNotNull('utilized_date')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day15_sent_month_no = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day15_sent_month_no[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day15_month_sent_no = [];
        foreach ($day15_sent_month_no as $month) {
            $day15_month_sent_no[$month] = $get_day15_sent_month_no[$month]->total_count ?? 0;
        }

        // Day 15 ( Order Joining Bonus day Wise) ********

        $get_day15_bonus_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('utilized_date', '>', DB::raw('day_15'))
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day15_bonus_no = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day15_bonus_no[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($get_day15_bonus_no as $date => $data) {
            $day15_bonus_no[$date] = $data->total_count;
        }


        // Day 15 ( Order Joining Bonus Month Wise) ********
        $get_day15_bonus_month_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_15,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('utilized_date', '>', DB::raw('day_15'))
            ->where('utilize', '1')
            ->where('journey', '0')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day15_bonus_month_no = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day15_bonus_month_no[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day15_month_bonus_no = [];
        foreach ($day15_bonus_month_no as $month) {
            $day15_month_bonus_no[$month] = $get_day15_bonus_month_no[$month]->total_count ?? 0;
        }

        // Day 15 (Recharge Day Wise) ********
        $data_day15_recharge_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m-%d') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') >= ?", [$startDate])
            ->when($endDate, function ($query, $endDate) {
                $query->whereRaw("DATE_FORMAT(day_15, '%Y-%m-%d') <= ?", [$endDate]);
            })
            ->where('first_recharge_date', '>', DB::raw('day_15'))
            ->where('recharge', '1')
            ->where('journey', '0')
            ->where('utilized_date', '<', DB::raw('day_15'))
            ->whereNotNull('utilized_date')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m-%d')"))
            ->get()
            ->keyBy('date');

        $day15_recharge_no = [];
        $currentDate = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        while ($currentDate <= $endDateObj) {
            $day15_recharge_no[$currentDate->format('Y-m-d')] = 0;
            $currentDate->modify('+1 day');
        }
        foreach ($data_day15_recharge_no as $date => $data) {
            $day15_recharge_no[$date] = $data->total_count;
        };


        // Day 15 (Recharge month Wise) ********
        $data_day15_recharge_month_no = DB::table('zadmin_loyalty_egg.sample_journey_utilized_recharge')
            // ->select('journey_id', 'customer_phone')
            ->selectRaw("DATE_FORMAT(day_15, '%Y-%m') as date, COUNT(*) as total_count")
            ->whereRaw("DATE_FORMAT(day_15,'%Y-%m') >= ?", $StartMonth)
            ->when($EndMonth, function ($query, $EndMonth) {
                $query->whereRaw("DATE_FORMAT(day_15,'%Y-%m') <= ?", $EndMonth);
            })
            ->where('first_recharge_date', '>', DB::raw('day_15'))
            ->where('recharge', '1')
            ->where('journey', '0')
            ->where('utilized_date', '<', DB::raw('day_15'))
            ->whereNotNull('utilized_date')
            ->groupBy(DB::raw("DATE_FORMAT(day_15, '%Y-%m')"))
            ->get()
            ->keyBy('date');

        $day15_recharge_month_no = [];
        $start = new DateTime($StartMonth);
        $end = new DateTime($EndMonth);

        while ($start <= $end) {
            $day15_recharge_month_no[] = $start->format('Y-m');
            $start->modify('+1 month');
        }
        $day15_month_recharge_no = [];
        foreach ($day15_recharge_month_no as $month) {
            $day15_month_recharge_no[$month] = $data_day15_recharge_month_no[$month]->total_count ?? 0;
        }



        return view('DateMessage', compact('result', 'months', 'DayWise', 'incompleteday', 'incompleteresult', 'samplebookdata', 'samplebookdata_month', 'incompleteday_msg_reminder', 'incompleteresult_msg_reminder', 'differences', 'differences_month', 'Nonservisable_result', 'Day_NonServisable', 'all_dates', 'get_data_btl_month', 'joining_Bonus_0', 'bonus_0_month', 'day0_recharege', 'recharge_0_month', 'day3_sentMessage', 'day3_month_sent', 'day3_bonus', 'day3_month_bonus', 'day3_recharge', 'recharge_day3_month', 'day6_sentMessage', 'day6_month_sent', 'day6_month_bonus', 'day6_bonus', 'day6_recharge', 'day6_month_recharge', 'day9_sentMessage', 'day9_month_sent', 'day9_bonus', 'day9_month_bonus', 'day9_recharge', 'day9_month_recharge', 'day12_sentMessage', 'day12_month_sent', 'day12_bonus', 'day12_month_bonus', 'day12_recharge', 'day12_month_recharge', 'day15_sentMessage', 'day15_month_sent', 'day15_bonus', 'day15_month_bonus', 'day15_recharge', 'day15_month_recharge', 'day9_month_sent_no', 'day9_sentMessage_no', 'day9_bonus_no', 'day9_month_bonus_no', 'day9_recharge_no', 'day9_month_recharge_no', 'day15_sentMessage_no', 'day15_month_sent_no', 'day15_bonus_no', 'day15_month_bonus_no', 'day15_recharge_no', 'day15_month_recharge_no'));
    }
}

//    " SELECT DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d'),COUNT(*) as send_msg FROM tbl_whatsapp_message_send_log WHERE whatsapp_template_id = 1394 AND DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') >= '2024-11-01' AND DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= '2024-11-02' GROUP BY DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d')"
// ->whereRaw("DATE_FORMAT(whatsapp_message_send_datetime,'%Y-%m-%d') <= ?", ['2024-11-02'])


// SELECT *  FROM `tbl_whatsapp_message_send_log_unregister` as a  INNER JOIN zadmin_loyalty_egg.ak_sample_product_order as b ON a.customer_phn_number = b.customer_phone WHERE a.`whatsapp_template_id` = 1527 AND b.created_at >= a.created_date
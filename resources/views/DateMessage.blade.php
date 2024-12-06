<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>DateTime</title>
</head>

<body>
    <div class="container">
        <div class="row mt-5 justify-content-center">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">OverAll DiGital Sampling</th>
                            @foreach ($months as $month)
                                <th>{{ $month }}</th>
                            @endforeach
                            @foreach ($DayWise as $day)
                                <th scope="col">{{ $day->message_date }}</th>
                            @endforeach
                            {{-- <th scope="col">2-Nov-24</th> --}}
                        </tr>
                        {{-- <tr>
                            
                            @foreach ($incomplete as $incompletes)
                                <th>{{ $incompletes }}</th>
                            @endforeach
                            @foreach ($incompleteday as $days)
                                <th scope="col">{{ $days->message_date }}</th>
                            @endforeach
                            
                        </tr> --}}
                    </thead>
                    <tbody>
                        <tr>
                            <td>Requested Of sample Message Sent</td>
                            @foreach ($result as $count)
                                <td>{{ $count }}</td>
                            @endforeach
                            @foreach ($DayWise as $day)
                                <td>{{ $day->send_msg }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Incomplete Message Sent</td>
                            @foreach ($incompleteresult as $counts)
                                <td>{{ $counts }}</td>
                            @endforeach
                            @foreach ($incompleteday as $days)
                                <td>{{ $days->send_msg }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Sample Book</td>
                            @foreach ($samplebookdata_month as $counts)
                                <td>{{ count($counts) }}</td>
                            @endforeach

                            @foreach ($samplebookdata as $book)
                                <td>{{ count($book) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Booked Ratio</td>
                            <?php
                            $ratios = [];
                            foreach ($incompleteresult as $key => $incompleteValue) {
                                if (isset($samplebookdata_month[$key]) && $incompleteValue > 0) {
                                    $sampleValue = count($samplebookdata_month[$key]);
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($incompleteday as $key => $day) {
                                if (isset($samplebookdata[$day->message_date]) && $day->send_msg > 0) {
                                    $sampleValue = count($samplebookdata[$day->message_date]);
                                    $dayratio[$day->message_date] = round($sampleValue / $day->send_msg, 2);
                                } else {
                                    $dayratio[$day->message_date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Incomplete Message Reminder</td>
                            @foreach ($incompleteresult_msg_reminder as $counts)
                                <td>{{ $counts }}</td>
                            @endforeach
                            @foreach ($incompleteday_msg_reminder as $days)
                                <td>{{ $days->send_msg }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Sample Book</td>
                            @foreach ($differences_month as $month)
                                <td>{{ count($month) }}</td>
                            @endforeach
                            @foreach ($differences as $book)
                                <td>{{ count($book) }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Booked Ratio</td>
                            <?php
                            $ratios = [];
                            foreach ($incompleteresult_msg_reminder as $key => $incompleteValue) {
                                if (isset($differences_month[$key]) && $incompleteValue > 0) {
                                    $sampleValue = count($differences_month[$key]);
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($incompleteday_msg_reminder as $key => $day) {
                                if (isset($differences[$day->message_date]) && $day->send_msg > 0) {
                                    $sampleValue = count($differences[$day->message_date]);
                                    $dayratio[$day->message_date] = round($sampleValue / $day->send_msg, 2);
                                } else {
                                    $dayratio[$day->message_date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>NonServisable Customer Message Sent</td>
                            @foreach ($Nonservisable_result as $count)
                                <td>{{ $count }}</td>
                            @endforeach
                            @foreach ($Day_NonServisable as $day)
                                <td>{{ $day->send_msg }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 0 (Delivery Of Sample Day)</td>
                            @foreach ($get_data_btl_month as $month)
                                <td>{{ $month->total_count }}</td>
                            @endforeach

                            @foreach ($all_dates as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 0 ( Ordered Joining Bonus )</td>
                            @foreach ($bonus_0_month as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($joining_Bonus_0 as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 0 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($get_data_btl_month as $key => $incompleteValue) {
                                if (isset($bonus_0_month[$key]) && $incompleteValue > 0) {
                                    $sampleValue = count($bonus_0_month[$key]);
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($all_dates as $date => $totalCount) {
                                if (isset($joining_Bonus_0[$date]) && $totalCount > 0) {
                                    $sampleValue = $joining_Bonus_0[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 0 ( Recharge )</td>
                            @foreach ($recharge_0_month as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day0_recharege as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 0 ( Recharege Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($get_data_btl_month as $key => $incompleteValue) {
                                if (isset($recharge_0_month[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $recharge_0_month[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day0_recharege as $date => $totalCount) {
                                if (isset($all_dates[$date]) && $totalCount > 0) {
                                    $sampleValue = $all_dates[$date];
                                    $dayratio[$date] = round($totalCount / $sampleValue, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 3 ( Sent Message )</td>
                            @foreach ($day3_month_sent as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day3_sentMessage as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 3 ( Joining Bonus )</td>
                            @foreach ($day3_month_bonus as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day3_bonus as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 3 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day3_month_sent as $key => $incompleteValue) {
                                if (isset($day3_month_bonus[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day3_month_bonus[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day3_sentMessage as $date => $totalCount) {
                                if (isset($day3_bonus[$date]) && $totalCount > 0) {
                                    $sampleValue = $day3_bonus[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 3 ( Recharge )</td>
                            @foreach ($recharge_day3_month as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day3_recharge as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 3 ( Recharge Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day3_month_sent as $key => $incompleteValue) {
                                if (isset($recharge_day3_month[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $recharge_day3_month[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day3_sentMessage as $date => $totalCount) {
                                if (isset($day3_recharge[$date]) && $totalCount > 0) {
                                    $sampleValue = $day3_recharge[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 6 ( Sent Message )</td>
                            @foreach ($day6_month_sent as $month)
                                <td>{{ $month }}</td>
                            @endforeach
                            @foreach ($day6_sentMessage as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 6 ( Joining Bonus )</td>
                            @foreach ($day6_month_bonus as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day6_bonus as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 6 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day6_month_sent as $key => $incompleteValue) {
                                if (isset($day6_month_bonus[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day6_month_bonus[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day6_sentMessage as $date => $totalCount) {
                                if (isset($day6_bonus[$date]) && $totalCount > 0) {
                                    $sampleValue = $day6_bonus[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 6 ( Recharge )</td>
                            @foreach ($day6_month_recharge as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day6_recharge as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 6 ( Recharge Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day6_month_sent as $key => $incompleteValue) {
                                if (isset($day6_month_recharge[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day6_month_recharge[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day6_sentMessage as $date => $totalCount) {
                                if (isset($day6_recharge[$date]) && $totalCount > 0) {
                                    $sampleValue = $day6_recharge[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 9 ( Sent Message )</td>
                            @foreach ($day9_month_sent as $month)
                                <td>{{ $month }}</td>
                            @endforeach
                            @foreach ($day9_sentMessage as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 9 ( Joining Bonus )</td>
                            @foreach ($day9_month_bonus as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day9_bonus as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 9 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day9_month_sent as $key => $incompleteValue) {
                                if (isset($day9_month_bonus[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day9_month_bonus[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day9_sentMessage as $date => $totalCount) {
                                if (isset($day9_bonus[$date]) && $totalCount > 0) {
                                    $sampleValue = $day9_bonus[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 9 ( Recharge )</td>
                            @foreach ($day9_month_recharge as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day9_recharge as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 9 ( Recharge Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day9_month_sent as $key => $incompleteValue) {
                                if (isset($day9_month_recharge[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day9_month_recharge[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day9_sentMessage as $date => $totalCount) {
                                if (isset($day9_recharge[$date]) && $totalCount > 0) {
                                    $sampleValue = $day9_recharge[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 12 ( Sent Message )</td>
                            @foreach ($day12_month_sent as $month)
                                <td>{{ $month }}</td>
                            @endforeach
                            @foreach ($day12_sentMessage as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 12 ( Joining Bonus )</td>
                            @foreach ($day12_month_bonus as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day12_bonus as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 12 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day12_month_sent as $key => $incompleteValue) {
                                if (isset($day12_month_bonus[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day12_month_bonus[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day12_sentMessage as $date => $totalCount) {
                                if (isset($day12_bonus[$date]) && $totalCount > 0) {
                                    $sampleValue = $day12_bonus[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 12 ( Recharge )</td>
                            @foreach ($day12_month_recharge as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day12_recharge as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 12 ( Recharge Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day12_month_sent as $key => $incompleteValue) {
                                if (isset($day12_month_recharge[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day12_month_recharge[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day12_sentMessage as $date => $totalCount) {
                                if (isset($day12_recharge[$date]) && $totalCount > 0) {
                                    $sampleValue = $day12_recharge[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Sent Message )</td>
                            @foreach ($day15_month_sent as $month)
                                <td>{{ $month }}</td>
                            @endforeach
                            @foreach ($day15_sentMessage as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Joining Bonus )</td>
                            @foreach ($day15_month_bonus as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day15_bonus as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day15_month_sent as $key => $incompleteValue) {
                                if (isset($day15_month_bonus[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day15_month_bonus[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day15_sentMessage as $date => $totalCount) {
                                if (isset($day15_bonus[$date]) && $totalCount > 0) {
                                    $sampleValue = $day15_bonus[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Recharge )</td>
                            @foreach ($day15_month_recharge as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day15_recharge as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Recharge Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day15_month_sent as $key => $incompleteValue) {
                                if (isset($day15_month_recharge[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day15_month_recharge[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day15_sentMessage as $date => $totalCount) {
                                if (isset($day15_recharge[$date]) && $totalCount > 0) {
                                    $sampleValue = $day15_recharge[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Joining Bonus - Didn’t Recharge</td>
                        </tr>
                        <tr>
                            <td>Day 09 ( Sent Message )</td>
                            @foreach ($day9_month_sent_no as $month)
                                <td>{{ $month }}</td>
                            @endforeach
                            @foreach ($day9_sentMessage_no as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 9 ( Joining Bonus )</td>
                            @foreach ($day9_month_bonus_no as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day9_bonus_no as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 09 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day9_month_sent_no as $key => $incompleteValue) {
                                if (isset($day9_month_bonus_no[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day9_month_bonus_no[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day9_sentMessage_no as $date => $totalCount) {
                                if (isset($day9_bonus_no[$date]) && $totalCount > 0) {
                                    $sampleValue = $day9_bonus_no[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 09 ( Recharge )</td>
                            @foreach ($day9_month_recharge_no as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day9_recharge_no as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 09 ( Recharge Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day9_month_sent_no as $key => $incompleteValue) {
                                if (isset($day9_month_recharge_no[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day9_month_recharge_no[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day9_sentMessage_no as $date => $totalCount) {
                                if (isset($day9_recharge_no[$date]) && $totalCount > 0) {
                                    $sampleValue = $day9_recharge_no[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Sent Message )</td>
                            @foreach ($day15_month_sent_no as $month)
                                <td>{{ $month }}</td>
                            @endforeach
                            @foreach ($day15_sentMessage_no as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Joining Bonus )</td>
                            @foreach ($day15_month_bonus_no as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day15_bonus_no as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day15_month_sent_no as $key => $incompleteValue) {
                                if (isset($day15_month_bonus_no[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day15_month_bonus_no[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day15_sentMessage_no as $date => $totalCount) {
                                if (isset($day15_bonus_no[$date]) && $totalCount > 0) {
                                    $sampleValue = $day15_bonus_no[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Recharge )</td>
                            @foreach ($day15_month_recharge_no as $month)
                                <td>{{ $month }}</td>
                            @endforeach

                            @foreach ($day15_recharge_no as $date => $count)
                                <td>{{ $count }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td>Day 15 ( Ordered Ratio )</td>
                            <?php
                            $ratios = [];
                            foreach ($day15_month_sent_no as $key => $incompleteValue) {
                                if (isset($day15_month_recharge_no[$key]) && $incompleteValue > 0) {
                                    $sampleValue = $day15_month_recharge_no[$key];
                                    $ratios[$key] = round($sampleValue / $incompleteValue, 2);
                                } else {
                                    $ratios[$key] = 0;
                                }
                            }
                            $dayratio = [];
                            foreach ($day15_sentMessage_no as $date => $totalCount) {
                                if (isset($day15_recharge_no[$date]) && $totalCount > 0) {
                                    $sampleValue = $day15_recharge_no[$date];
                                    $dayratio[$date] = round($sampleValue / $totalCount, 2);
                                } else {
                                    $dayratio[$date] = 0;
                                }
                            }
                            ?>
                            @foreach ($ratios as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                            @foreach ($dayratio as $ratio)
                                <td>{{ $ratio }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = strtotime('2019-04-01 00:00:00');
        $end = strtotime('2050-03-31 00:00:00');

        $ret=array();
        $temp = $start;
        while($temp <= $end){
          $ret[] = [ 'date' => date('Y-m-d H:i:s', $temp)];
          $temp = strtotime('+1 day', $temp);
        }// end while

        DB::table('calendars')->insert($ret);
    }
}

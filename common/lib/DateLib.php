<?php
    namespace common\lib;

    class DateLib{

        public static function getMonth(){
            $month = [
                1 => 'Jan',
                2 => 'Feb',
                3 => 'Mar',
                4 => 'Apr',
                5 => 'May',
                6 => 'Jun',
                7 => 'Jul',
                8 => 'Aug',
                9 => 'Sep',
                10 => 'Oct',
                11 => 'Nov',
                12 => 'Dec',
            ];

            return $month;
        }

        public static function dateFormat($date){

            $result = date("d-M-Y", strtotime($date));

            return $result;
        }

        public static function getAge($date){

            $result = date('Y') - date('Y', strtotime($date));

            return $result;
        }

        public static function getEndDate($date){

            if($date != ''){
                $result = date("d-M-Y", strtotime($date));
            }else{
                $result = '';
            }

            return $result;
        }

        public static function getWorkDate($start_date,$end_date){
            if($end_date == ''){
                $endDate = date("Y-m-d");
            }else{
                $endDate = $end_date;
            }
            $date_diff = strtotime(str_replace('/', '-', $endDate)) - strtotime(str_replace('/', '-', $start_date));
            $year = round($date_diff / (60 * 60 * 24)) / 365;
            $month = $year*12;
            $result = number_format($month);
            return $result;
        }

    }
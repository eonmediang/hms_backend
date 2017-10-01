<?php

class Uuid
{
    public static function genUid($lid, $opts = [])
    {
        $sid = $opts['sid'] ?? 1;
        $ts_len = $opts['ts_len'] ?? 33;
        $sid_len = $opts['sid_len'] ?? 12;
        $lid_len = $opts['lid_len'] ?? 19;
        $use_ts = $opts['use_timestamp'] ?? false;

        $ts = time();

        if ($use_ts) {
            $total_bits = $ts_len + $sid_len + $lid_len;    
            $a = $ts << ($total_bits - $ts_len);
            $b = $sid << ($total_bits - $ts_len - $sid_len);
            $c = $lid << 0;
            return ( $a | $b | $c );
        }

        $total_bits = $sid_len + $lid_len;
        $a = $sid << ($total_bits - $sid_len);
        $b = $lid << 0;
        
        return ( $a | $b );
    }

    public static function getSid($uid = 1)
    {
        $uid = (int) $this->method_args_url;
        $a = $uid >> (64 - 33 - 12);
        $e = 2**12 - 1;
        echo ($a & $e);
    }

    // public static function parseUid(
    //     $uid,
    //     $ts_len = 33,
    //     $sid_len = 12,
    //     $lid_len = 19
    // ) {
    
    //     $uid = (int) $uid;
    //     $obj = new stdClass;
    //     $obj->sid = $uid >> (64 - $ts_len - $sid_len)
    //                 & (2**$sid_len - 1);

    //     $obj->lid = $uid >> 0 & (2**$lid_len - 1);

    //     return $obj;
    // }

    public static function parseUid($uid, $opts)
    {
        $ts_len = $opts['ts_len'] ?? 33;
        $sid_len = $opts['sid_len'] ?? 12;
        $lid_len = $opts['lid_len'] ?? 19;
        $has_ts = $opts['has_timestamp'] ?? false;
    
        $uid = (int) $uid;
        $obj = new stdClass;

        if ($has_ts){
            $total_bits = $ts_len + $sid_len + $lid_len; 
            $obj->sid = $uid >> ($total_bits - $ts_len - $sid_len)
            & (2**$sid_len - 1);

            $obj->lid = $uid >> 0 & (2**$lid_len - 1);

            return $obj;
        }

        $total_bits = $sid_len + $lid_len;

        $obj->sid = $uid >> ($total_bits - $sid_len)
                    & (2**$sid_len - 1);

        $obj->lid = $uid >> 0 & (2**$lid_len - 1);

        return $obj;
    }
}

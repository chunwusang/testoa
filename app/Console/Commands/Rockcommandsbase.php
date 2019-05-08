<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;


class Rockcommandsbase extends Command
{
    
    public function echomsg($smg)
    {
		echo '['.date('Y-m-d H:i:s').'] '.$smg.''.chr(10).chr(10);
    }
	
}

<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('relay:record-usage')->hourly();

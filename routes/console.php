<?php

use App\Console\Commands\FetchNews;

Schedule::command(FetchNews::class)->hourly();

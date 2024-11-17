<?php

namespace App\Console\Commands;

use App\Service\NewsAggregatorService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch news articles from various sources';

    /**
     * The console progress bar.
     *
     * @var ProgressBar $progressBar
     */
    public ProgressBar $progressBar;

    /**
     * Set custom format for progress bar to display message above the progress bar
    */
    public function __construct()
    {
        parent::__construct();
        ProgressBar::setFormatDefinition('message_progress_bar',
            "<question>%message%</question> \n %current%/%max% [%bar%] %percent:3s%% %elapsed:16s%");

    }


    /**
     * Execute the console command.
     */
    public function handle(NewsAggregatorService $newsAggregatorService): int
    {
        $this->progressBar = $this->output->createProgressBar();
        $this->progressBar->setFormat('message_progress_bar');
        $this->progressBar->setMessage('Starting Service ...');
        $this->progressBar->start();

        try {
            $newsAggregatorService->fetchAndStoreNews($this->progressBar);

            $this->newLine();
            $this->info('News articles fetched and stored successfully.');

        } catch (\Exception $e) {
            $this->error('An error occurred while fetching news: ' . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

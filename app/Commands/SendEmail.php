<?php namespace App\Commands;

use App\Commands\Command;
use App\User;
use \Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Support\Facades\Queue;

class SendEmail extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $content;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($content)
	{
		//
		$this->content = $content;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		//
		$this->SendMailNotification($this->content);
	}

	public function SendMailNotification($content){
        
    }
}

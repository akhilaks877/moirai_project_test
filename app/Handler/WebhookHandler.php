<?php

namespace App\Handler;



use Spatie\WebhookClient\ProcessWebhookJob;



class WebhookHandler extends ProcessWebhookJob
{
	public function handle()
	{
		
		dd("this is my page");
		
	}
}
<?php

namespace HsuBuu\Merchant;

use Illuminate\Support\ServiceProvider;

class MerchantServiceProvider extends ServiceProvider
{
	public function boot()
	{
		include __DIR__ . '/routes.php';

		$this->loadViewsFrom(__DIR__ . '/Views', 'merchant');
	}

	public function register()
	{
		$this->app['merchant'] = $this->app->share(function ($app) {
			return new Merchant;
		});
	}
}
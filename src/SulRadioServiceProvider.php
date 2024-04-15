<?php

namespace Oka6\SulRadio;

use Illuminate\Support\ServiceProvider;

class SulRadioServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->loadViewsFrom(__DIR__ . '/Resources/views', 'SulRadio');
		$this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
		$this->loadRoutesFrom(__DIR__ . '/Http/Routes/web.php');
		
		$this->publishes([
			__DIR__ . '/Public' => public_path('vendor'),
		], 'public');
		
		$this->mergeConfigFrom(
			__DIR__ . '/Config/database.php', 'database.connections'
		);
		$this->mergeConfigFrom(
			__DIR__ . '/Config/sulradio.php', 'sulradio'
		);
		
		$this->mergeConfigFrom(
			__DIR__ . '/Config/profile_type.php', 'admin.profile_type'
		);

		$this->mergeConfigFrom(
			__DIR__ . '/Config/profile_type.php', 'sulradio.profile_type'
		);

		$this->mergeConfigFrom(
			__DIR__ . '/Config/multimail.php', 'multimail'
		);
		if (file_exists($file = __DIR__.'/Helpers/helper_function.php')) {
			require $file;
		}

		if (php_sapi_name() != 'cli') {
			$this->setObservers();
		}
		
		
	}
	
	/**
	 * Merge the given configuration with the existing configuration.
	 *
	 * @param string $path
	 * @param string $key
	 * @return void
	 */
	protected function mergeConfigFrom($path, $key) {
		$config = $this->app['config']->get($key, []);
		if ($key == 'database.connections' && isset($config['sulradio']) == false) {
			$this->app['config']->set($key, array_merge($config, require $path));
		}elseif ($key == 'admin.profile_type') {
			$this->app['config']->set($key, array_merge($config, require $path));
		}elseif ($key == 'sulradio') {
			$this->app['config']->set($key, array_merge($config, require $path));
		}elseif ($key == 'sulradio.profile_type') {
			$this->app['config']->set($key, array_merge($config, require $path));
		}elseif ($key == 'multimail') {
			$this->app['config']->set($key, array_merge($config, require $path));
		}
	}
	
	protected function setObservers() {
	}
}

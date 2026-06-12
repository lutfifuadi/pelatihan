<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    // Load menu JSON files
    $menuBase = base_path('resources/menu');

    $verticalMenuJson = file_get_contents("$menuBase/verticalMenu.json");
    $verticalMenuData = json_decode($verticalMenuJson);

    $horizontalMenuJson = file_get_contents("$menuBase/horizontalMenu.json");
    $horizontalMenuData = json_decode($horizontalMenuJson);

    // Load per-role menu
    $adminMenuJson     = file_get_contents("$menuBase/verticalMenu-admin.json");
    $instrukturMenuJson= file_get_contents("$menuBase/verticalMenu-instruktur.json");
    $pesertaMenuJson   = file_get_contents("$menuBase/verticalMenu-peserta.json");
    $koordinatorMenuJson= file_get_contents("$menuBase/verticalMenu-koordinator.json");

    $menuByRole = [
      'admin'       => json_decode($adminMenuJson),
      'instruktur'  => json_decode($instrukturMenuJson),
      'peserta'     => json_decode($pesertaMenuJson),
      'koordinator' => json_decode($koordinatorMenuJson),
    ];

    // Share all menuData to all the views
    $this->app->make('view')->share('menuData', [$verticalMenuData, $horizontalMenuData]);
    $this->app->make('view')->share('menuByRole', $menuByRole);
  }
}

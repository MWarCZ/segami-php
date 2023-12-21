<?php
namespace MWarCZ\Segami\Plugin;

use MWarCZ\Segami\Plugin\CorePlugin;
use MWarCZ\Segami\Plugin\Plugin;
use MWarCZ\Segami\Props\CoreProps;
use MWarCZ\Segami\Props\Props;

class PluginManager {
  /** @var string */
  public $core_key = '';
  /** @var CorePlugin */
  public $core_plugin = null;
  /** @var Plugin[] */
  public $a_other_plugin = [];
  /** @var CoreProps */
  public $core_props = null;
  /** @var Props[] */
  public $a_other_props = [];
  /** @var Props[] */
  public $a_all_props = [];

  public function __construct($core_key = '', $core_plugin = null, $a_other_plugin = [], $core_props = null, $a_other_props = [], $a_all_props = []) {
    $this->core_key = $core_key;
    $this->core_plugin = $core_plugin;
    $this->a_other_plugin = $a_other_plugin;
    $this->core_props = $core_props;
    $this->a_other_props = $a_other_props;
    $this->a_all_props = $a_all_props;
  }

  public static function parseQuery($map_plugin, $full_query) {
    $core_key = '';
    $core_plugin = null;
    $a_other_plugin = [];
    $core_props = null;
    $a_other_props = [];
    $a_all_props = [];
    // Oddělit základní plugin od ostatních
    foreach ($map_plugin as $key => $plugin) {
      if ($plugin instanceof CorePlugin) {
        $core_key = $key;
        $core_plugin = $plugin;
      } else {
        $a_other_plugin[$key] = $plugin;
      }
    }
    if (!$core_plugin)
      throw new \Exception('Chybí CorePlugin');
    // Základní plugin
    if (!$core_plugin->getFactory()->validQuery($full_query)) {
      throw new \Exception('Chybný formát pro CorePlugin');
    }
    $core_props = $core_plugin->getFactory()->parseQuery($full_query);
    $a_all_props[$core_key] = $core_props;
    // Procházení ostatních vlastností a hledán vhodný plugin
    $a_props_query = $core_props->getProps();
    foreach ($a_props_query as $props_query) {
      $next_key = '';
      $next_plugin = null;
      foreach ($a_other_plugin as $key => $plugin) {
        if ($plugin->getFactory()->validQuery($props_query)) {
          $next_key = $key;
          $next_plugin = $plugin;
        }
      }
      if (!$next_plugin)
        throw new \Exception('Nebyl nalezen správný Plugin');
      $a_other_props[$next_key] = $next_plugin->getFactory()->parseQuery($props_query);
      $a_all_props[$next_key] = $a_other_props[$next_key];
    }

    return new self($core_key, $core_plugin, $a_other_plugin, $core_props, $a_other_props, $a_all_props);
  }
  public function toQuery() {
    $a_props_query = [];
    foreach ($this->a_other_props as $key => $props) {
      $a_props_query[] = $this->a_other_plugin[$key]->getFactory()->createQuery($props);
    }
    $this->core_props->setProps($a_props_query);
    return $this->core_plugin->getFactory()->createQuery($this->core_props);
  }

}

<?php
  /**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTML;

  class si_sitemap_tree_manufacturers {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);

      $this->title = CLICSHOPPING::getDef('modules_sitemap_tree_manufacturers_title');
      $this->description = CLICSHOPPING::getDef('modules_sitemap_tree_manufacturers_description');

      if ( defined('MODULES_SITEMAP_TREE_MANUFACTURERS_STATUS') ) {
        $this->sort_order = MODULES_SITEMAP_TREE_MANUFACTURERS_SORT_ORDER;
        $this->enabled = (MODULES_SITEMAP_TREE_MANUFACTURERS_STATUS == 'True');
      }
    }

    public function execute() {
      $content_width = (int)MODULES_SITEMAP_TREE_MANUFACTURERS_CONTENT_WIDTH;

      $CLICSHOPPING_Template = Registry::get('Template');
      $CLICSHOPPING_Manufacturers = Registry::get('Manufacturers');


// essentiel sinon conflit
      if (isset($_GET['SiteMap']))  {
        $manufactuer_array = $CLICSHOPPING_Manufacturers->getAll();

        $sitemap_tree = '<!-- start sitemap_tree -->' . "\n";

        $position = MODULES_SITEMAP_TREE_MANUFACTURERS_POSITION;

        $sitemap_tree .= '<div class="col-md-' . $content_width . ' ' .  $position . '">';
        $sitemap_tree .= '<div class="separator"></div>';
        $sitemap_tree .= '<div class="sitemapCategoryTreeTittleManufacturers"><h3>' . CLICSHOPPING::getDef('modules_sitemap_tree_manufacturers_title_heading') . '</3></div>';
        $sitemap_tree .= '<div class="SitemapCategoryTreeBlockManufacturers">';
        $sitemap_tree .= '<div class="SitemapCategoryTreeManufacturers">';
        $sitemap_tree .= '<i class="fas fa-home fa-2x"></i>&nbsp;' . HTML::link(CLICSHOPPING::link(), CLICSHOPPING::getDef('modules_sitemap_tree_manufacturers_header_title_top'));

        $sitemap_tree .= '<ul class="list-group list-group-SitemapTreeManufacturers">';

        if (is_array($manufactuer_array)) {
          foreach ($manufactuer_array as $item) {
            $manufacturer_url = $CLICSHOPPING_Manufacturers->getManufacturerUrlRewrited()->getManufacturerUrl($item['id']);
            $manufacturer_name = HTML::link($manufacturer_url,  $item['name']);

            $sitemap_tree .= '<li class="list-group-itemSitemapTreeManufacturers">' . $manufacturer_name . '</li>';
          }
        }

        $sitemap_tree .= '</ul>';
        $sitemap_tree .= '</div>';
        $sitemap_tree .= '</div>';
        $sitemap_tree .= '</div>';

       $sitemap_tree .= '<!-- end sitemap_tree -->' . "\n";

        $CLICSHOPPING_Template->addBlock($sitemap_tree, $this->group);
      }

    } // end public function

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULES_SITEMAP_TREE_MANUFACTURERS_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Do you want to enable this module ?',
          'configuration_key' => 'MODULES_SITEMAP_TREE_MANUFACTURERS_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Do you want to enable this module in your shop ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Please select the width of the display?',
          'configuration_key' => 'MODULES_SITEMAP_TREE_MANUFACTURERS_CONTENT_WIDTH',
          'configuration_value' => '12',
          'configuration_description' => 'Please enter a number between 1 and 12',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_content_module_width_pull_down',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Where you want to display the sitemap ?',
          'configuration_key' => 'MODULES_SITEMAP_TREE_MANUFACTURERS_POSITION',
          'configuration_value' => 'float-md-none',
          'configuration_description' => 'Select where do you want to display the sitemap',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'float-md-left\', \'float-md-right\', \'float-md-none\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Sort order',
          'configuration_key' => 'MODULES_SITEMAP_TREE_MANUFACTURERS_SORT_ORDER',
          'configuration_value' => '120',
          'configuration_description' => 'Sort order of display. Lowest is displayed first. The sort order must be different on every module',
          'configuration_group_id' => '6',
          'sort_order' => '4',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULES_SITEMAP_TREE_MANUFACTURERS_STATUS',
                   'MODULES_SITEMAP_TREE_MANUFACTURERS_CONTENT_WIDTH',
                   'MODULES_SITEMAP_TREE_MANUFACTURERS_POSITION',
                   'MODULES_SITEMAP_TREE_MANUFACTURERS_SORT_ORDER'
      );
    }
  }

<?php
/*
 * This file is part of the sfImaginablePlugin package.
 *
 * (c) 2008 Ivan Tanev <vankat.t@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$plugin_name = 'sfImaginablePlugin';

if (in_array('sfImaginable', sfConfig::get('sf_enabled_modules')) && sfConfig::get('app_sfImaginable_route_register', true))
{
  $this->dispatcher->connect('routing.load_configuration', array('sfImaginablePluginRouting', 'listenToRoutingLoadConfigurationEvent'));
}

// Resources paths, you can modifiy those to fit your needs
sfConfig::set( 'sf_imaginable_css_dir',    '/sfImaginablePlugin/' . 'css'    );
sfConfig::set( 'sf_imaginable_images_dir', '/sfImaginablePlugin/' . 'images' );
sfConfig::set( 'sf_imaginable_js_dir',     '/sfImaginablePlugin/' . 'js'     );
sfConfig::set( 'sf_imaginable_swf_dir',    '/sfImaginablePlugin/' . 'swf'    ); 
// And now for jquery, set proper paths if jQueryReloaded plugin is not present
sfConfig::set( 'sf_imaginable_jquery_ui',  'ui/jquery-ui-1.7.2.custom.min.js');
sfConfig::set( 'jquery_web_dir', sfConfig::get('jquery_web_dir', '/sfImaginablePlugin/jquery')  );
sfConfig::set( 'jquery_core',    sfConfig::get('jquery_core',    'jquery-1.3.2.min.js')         );
// And Uploadify script
sfConfig::set( 'sf_imaginable_uploadify_js', 'jquery.uploadify.min.js');
// And custom handles
sfConfig::set( 'sf_imaginable_handlers_js',   'handlers.js');

 
sfPropelBehavior::registerHooks('sfImaginableBehavior', array (
 ':delete:post' => array ('sfImaginableBehavior', 'postDelete'),
)); 


sfPropelBehavior::registerMethods('sfImaginableBehavior', array (
  array ('sfImaginableBehavior', 'getImage'),  
  array ('sfImaginableBehavior', 'getFirstImage'),  
  array ('sfImaginableBehavior', 'getImages'),  
  array ('sfImaginableBehavior', 'addImage'),  
  array ('sfImaginableBehavior', 'removeAllImages'),
  array ('sfImaginableBehavior', 'removeImageByPosition'),
  array ('sfImaginableBehavior', 'reorderImages'),  
  array ('sfImaginableBehavior', 'getNbImages'),  
));
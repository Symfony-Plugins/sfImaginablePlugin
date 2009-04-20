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


$this->dispatcher->connect('routing.load_configuration', array('sfImaginablePluginRouting', 'listenToRoutingLoadConfigurationEvent'));

// Resources paths, you can modifiy those to fit your needs
sfConfig::set( 'sf_imaginable_css_dir',    '/sfImaginablePlugin/' . 'css'    );
sfConfig::set( 'sf_imaginable_images_dir', '/sfImaginablePlugin/' . 'images' );
sfConfig::set( 'sf_imaginable_js_dir',     '/sfImaginablePlugin/' . 'js'     );
sfConfig::set( 'sf_imaginable_swf_dir',    '/sfImaginablePlugin/' . 'swf'    ); 
 
 
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
<?php
/**
 * File Module.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   2.0.1
 * @link      http://www.sweelix.net
 * @category  dashboard
 * @package   sweelix.yii1.admin.dashboard
 */

namespace sweelix\yii1\admin\dashboard;
use sweelix\yii1\admin\core\components\BaseModule;

/**
 * Class Module
 *
 * This module is the base container for all admin submodules
 * @see Module in components.
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   2.0.1
 * @link      http://www.sweelix.net
 * @category  dashboard
 * @package   sweelix.yii1.admin.dashboard
 * @since     1.0.0
 */
class Module extends BaseModule {
	/**
	 * @var string controllers namespace
	 */
	public $controllerNamespace = 'sweelix\yii1\admin\dashboard\controllers';
	/**
	 * Init the module with specific information.
	 * @see CModule::init()
	 *
	 * @return void
	 * @since  1.2.0
	 */
	protected function init() {
		$this->basePath = __DIR__;
		\Yii::setPathOfAlias($this->getShortId(), __DIR__);
		\Yii::app()->getMessages()->extensionPaths[$this->getShortId()] = $this->getShortId().'.messages';
		parent::init();
	}
	private $_cacheId;
	/**
	 * define the cms cache id
	 *
	 * @param string $cacheId id of cms cache
	 *
	 * @return void
	 * @since  1.11.0
	 */
	public function setCacheId($cacheId) {
		$this->_cacheId = $cacheId;
	}

	/**
	 * get current cms cache id
	 *
	 * @return string
	 * @since  1.11.0
	 */
	public function getCacheId() {
		return $this->_cacheId;
	}
	private $_cache;

	/**
	 * Get cache component if everything
	 * was set correctly
	 *
	 * @return CCache
	 * @since  1.11.0
	 */
	public function getCache() {
		if(($this->_cache === null) && ($this->_cacheId !== null)) {
			$this->_cache = \Yii::app()->getComponent($this->_cacheId);
		}
		return $this->_cache;
	}
}

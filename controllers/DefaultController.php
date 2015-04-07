<?php
/**
 * File DefaultController.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  controllers
 * @package   sweelix.yii1.admin.dashboard.controllers
 */

namespace sweelix\yii1\admin\dashboard\controllers;

use sweelix\yii1\admin\core\web\Controller;
use sweelix\yii1\ext\db\CriteriaBuilder;
use sweelix\yii1\ext\entities\Node;
use sweelix\yii1\ext\entities\Content;
use sweelix\yii1\ext\entities\Group;
use sweelix\yii1\ext\entities\Tag;
use sweelix\yii1\web\helpers\Html;
use Yii;

/**
 * Class DefaultController
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  controllers
 * @package   sweelix.yii1.admin.dashboard.controllers
 */
class DefaultController extends Controller
{

    /**
     * Default action. Should redirect to real action
     *
     * @return void
     */
    public function actionIndex()
    {
        Yii::trace(__METHOD__ . '()', 'sweelix.yii1.admin.dashboard.controllers');

        $orphansBuilder = new CriteriaBuilder('content');
        $orphansBuilder->filterBy('nodeId', null);
        $orphansBuilder->orderBy('contentStartDate', 'desc');
        $orphansBuilder->orderBy('contentId', 'desc');

        $expiredBuilder = new CriteriaBuilder('content');
        $expiredBuilder->filterBy('contentEndDate', new \CDbExpression('NOW()'), '<');
        $expiredBuilder->filterBy('contentStatus', 'online');
        $expiredBuilder->orderBy('contentEndDate', 'desc');

        $dyingBuilder = new CriteriaBuilder('content');
        $dyingBuilder->filterBy('contentEndDate', new \CDbExpression('NOW()'), '>');
        $dyingBuilder->filterBy('contentStatus', 'online');
        $dyingBuilder->orderBy('contentEndDate', 'asc');

        if ((Yii::app()->getModule('sweeft')->hasModule('structure') === true)
            && (Yii::app()->user->checkAccess('structure') === true)) {
            $route = array('/sweeft/structure/content/property');
        } else {
            $route = null;
        }

        $this->render('index', array(
            'orphans' => $orphansBuilder->getActiveDataProvider(),
            'expired' => $expiredBuilder->getActiveDataProvider(),
            'dying' => $dyingBuilder->getActiveDataProvider(),
            'tables' => $this->databaseStatus['tables'],
            'procedures' => $this->databaseStatus['procedures'],
            'contentRoute' => $route,
        ));
    }

    private $databaseStatus;

    /**
     * Check database status
     *
     * @return array
     * @since  2.0.0
     */
    public function getDatabaseStatus()
    {
        if ($this->databaseStatus === null) {
            $this->databaseStatus = array(
                'tables' => array(
                    'authors' => false,
                    'contentMeta' => false,
                    'contentTag' => false,
                    'contents' => false,
                    'groups' => false,
                    'languages' => false,
                    'metas' => false,
                    'nodeMeta' => false,
                    'nodeTag' => false,
                    'nodes' => false,
                    'tags' => false,
                    'templates' => false,
                    'urls' => false
                ),
                'views' => null,
                'procedures' => array(
                    'spContentMove' => false,
                    'spContentReorder' => false,
                    'spFlushUrl' => false,
                    'spNodeDelete' => false,
                    'spNodeInsert' => false,
                    'spNodeMove' => false
                ),
            );
            $procedures = Yii::app()->db->createCommand('SHOW PROCEDURE STATUS')->queryAll();
            foreach ($procedures as $proc) {
                if (isset($this->databaseStatus['procedures'][$proc['Name']]) === true) {
                    $this->databaseStatus['procedures'][$proc['Name']] = true;
                }
            }
            $tables = Yii::app()->db->createCommand('SHOW TABLES')->queryAll(false);
            foreach ($tables as $table) {
                if (isset($this->databaseStatus['tables'][$table[0]]) === true) {
                    $this->databaseStatus['tables'][$table[0]] = true;
                }
            }
        }
        return $this->databaseStatus;
    }

    /**
     * Flush app cache, usefull when editing html
     *
     * @return void
     */
    public function actionFlushCache()
    {
        Yii::trace(__METHOD__ . '()', 'sweelix.yii1.admin.dashboard.controllers');
        $cache = $this->getModule()->getCache();
        if ($cache !== null) {
            $cache->flush();
            $this->renderJs(Html::raiseEvent('showNotice', array(
                'title' => Yii::t('dashboard', 'flush'),
                'text' => Yii::t('dashboard', 'Cache was flushed successfully'),
                'cssClass' => 'inverse'
            )));
        }
    }

    /**
     * Flush url in order to rebuild something cool
     *
     * @return void
     */
    public function actionFlushUrl()
    {
        Yii::trace(__METHOD__ . '()', 'sweelix.yii1.admin.dashboard.controllers');
        Yii::app()->getDb()->createCommand('CALL spFlushUrl()')->execute();
        $this->renderJs(Html::raiseEvent('showNotice', array(
            'title' => Yii::t('dashboard', 'flush'),
            'text' => Yii::t('dashboard', 'URLs were flushed successfully'),
            'cssClass' => 'inverse'
        )));
    }

    /**
     * Reindex all elements
     *
     * @return void
     */
    public function actionReindex()
    {
        Yii::trace(__METHOD__ . '()', 'sweelix.yii1.admin.dashboard.controllers');
        set_time_limit(0);
        $contents = Content::model()->findAll();
        foreach ($contents as $element) {
            $element->save();
        }
        $nodes = Node::model()->findAll();
        foreach ($nodes as $element) {
            $element->save();
        }
        $tags = Tag::model()->findAll();
        foreach ($tags as $element) {
            $element->save();
        }
        $groups = Group::model()->findAll();
        foreach ($groups as $element) {
            $element->save();
        }
        $this->renderJs(Html::raiseEvent('showNotice', array(
            'title' => Yii::t('dashboard', 'flush'),
            'text' => Yii::t('dashboard', 'Database was re-indexed successfully'),
            'cssClass' => 'inverse'
        )));
    }

    /**
     * Define filtering rules
     *
     * @return array
     */
    public function filters()
    {
        return array('accessControl');
    }

    /**
     * Define access rules / rbac stuff
     *
     * @return array
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'roles' => array($this->getModule()->getName())
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }
}

<?php
/**
 * File index.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.1.0
 * @link      http://www.sweelix.net
 * @category  views
 * @package   sweelix.yii1.admin.dashboard.views.default
 */
use sweelix\yii1\web\helpers\Html;
?>
<section>
	<div id="content">
		<div class="widgetContainer">
			<?php
				$this->widget('sweelix\yii1\ext\web\widgets\ListView', array(
					'dataProvider' => $orphans,
					'hideOnEmpty' => true,
					'itemView' => '_itemView',
					'headerView' => '_headerView',
					'footerView' => '_footerView',
					// 'summaryView' => '_summaryView',
					'template' => "<div class=\"widget\">\n<table>\n<thead>\n{header}\n{summary}\n</thead>\n<tbody>\n{items}\n</tbody>\n<tfoot>\n{footer}\n</tfoot>\n</table>\n</div>",
					'viewData' => array(
						'contentRoute' => $contentRoute,
						'title' => '{n} orphan|{n} orphans',
					),
				));
			?>
			<?php
				$this->widget('sweelix\yii1\ext\web\widgets\ListView', array(
					'dataProvider' => $dying,
					'hideOnEmpty' => true,
					'itemView' => '_itemView',
					'headerView' => '_headerView',
					'footerView' => '_footerView',
					'template' => "<div class=\"widget\">\n<table>\n<thead>\n{header}\n{summary}\n</thead>\n<tbody>\n{items}\n</tbody>\n<tfoot>\n{footer}\n</tfoot>\n</table>\n</div>",
					'viewData' => array(
						'contentRoute' => $contentRoute,
						'title' => 'End of life content|End of life contents',
					),
				));
			?>
			<?php
				$this->widget('sweelix\yii1\ext\web\widgets\ListView', array(
					'dataProvider' => $expired,
					'hideOnEmpty' => true,
					'itemView' => '_itemView',
					'headerView' => '_headerView',
					'footerView' => '_footerView',
					'template' => "<div class=\"widget\">\n<table>\n<thead>\n{header}\n{summary}\n</thead>\n<tbody>\n{items}\n</tbody>\n<tfoot>\n{footer}\n</tfoot>\n</table>\n</div>",
					'viewData' => array(
						'contentRoute' => $contentRoute,
						'title' => 'Expired content|Expired contents',
					),
				));
			?>

			<div class="widget">
				<table>
					<thead>
						<tr>
							<th colspan="2"><?php echo Yii::t('dashboard', 'Special operations'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php $status = ($this->getModule()->getCache() === null)?false:true; ?>
						<?php echo Html::openTag('tr', array('class' => ($status?'success':'danger'))); ?>
							<td><?php echo Yii::t('dashboard', 'Cache')?></td>
							<?php if($status == true): ?>
								<td style="text-align:center">
									<?php echo Html::link(
											Html::tag('span', array('class' => 'icon-refresh light'), Yii::t('dashboard', 'flush'), true),
											Html::raiseAjaxRefreshUrl(null, array('flushCache')),
											array('class' => 'success button')
									); ?>
								</td>
							<?php else: ?>
								<td style="text-align:center">
									<?php echo Yii::t('dashboard', 'Cache cannot be flushed'); ?>
								</td>
							<?php endif; ?>
						</tr>
						<?php $status = ($procedures['spFlushUrl'] == true)?true:false; ?>
						<?php echo Html::openTag('tr', array('class' => ($status?'success':'danger'))); ?>
							<td><?php echo Yii::t('dashboard', 'URLs')?></td>
							<?php if($status == true): ?>
							<td style="text-align:center">
								<?php echo Html::link(
										Html::tag('span', array('class' => 'icon-refresh light'), Yii::t('dashboard', 'flush'), true),
										Html::raiseAjaxRefreshUrl(null, array('flushUrl')),
										array('class' => 'success button small')
								); ?>
							</td>
							<?php else: ?>
							<td><?php echo Yii::t('dashboard', 'URLs cannot be flushed'); ?></td>
							<?php endif; ?>
						</tr>
						<?php echo Html::openTag('tr', array('class' =>'success')); ?>
							<td><?php echo Yii::t('dashboard', 'Indexer')?></td>
							<td style="text-align:center">
								<?php echo Html::link(
										Html::tag('span', array('class' => 'icon-refresh light'), Yii::t('dashboard', 'flush'), true),
										Html::raiseAjaxRefreshUrl(null, array('reindex')),
										array('class' => 'success button small')
								); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="widget">
				<table>
					<thead>
						<tr>
							<th colspan="3"><?php echo Yii::t('dashboard', 'Sweelix stored procedures'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($procedures as $name => $status): ?>
						<?php echo Html::openTag('tr', array('class' => ($status?'success':'danger'))); ?>
							<td><?php echo $status?Yii::t('dashboard', 'OK'):Yii::t('dashboard', 'KO') ?></td>
							<td><?php echo Yii::t('dashboard', 'Proc')?></td>
							<td><?php echo $name ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="widget">
				<table>
					<thead>
						<tr>
							<th colspan="3"><?php echo Yii::t('dashboard', 'Sweelix tables'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($tables as $name => $status): ?>
						<?php echo Html::openTag('tr', array('class' => ($status?'success':'danger'))); ?>
							<td><?php echo $status?Yii::t('dashboard', 'OK'):Yii::t('dashboard', 'KO') ?></td>
							<td><?php echo Yii::t('dashboard', 'Table')?></td>
							<td><?php echo $name ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

		</div>
		<div class="clear"></div>
	</div>
</section>
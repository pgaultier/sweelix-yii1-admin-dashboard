<?php
/**
 * File _itemView.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   3.0.0
 * @link      http://www.sweelix.net
 * @category  views
 * @package   sweelix.yii1.admin.dashboard.views.default
 */
use sweelix\yii1\web\helpers\Html;

$class = $data->contentStatus . (($data->isPublishable() === false)?' unpublished':'');
?>
<?php echo Html::openTag('tr', array('class' => $class)); ?>
	<td class="main-id">
		<?php echo $data->contentId; ?>
	</td>
	<td class="status">
		<?php echo $data->contentStatus; ?>
	</td>
	<td>
		<?php if($contentRoute === null): ?>
			<?php echo $data->contentTitle; ?>
		<?php else: ?>
			<?php
				$contentRoute['contentId'] = $data->contentId;
				echo Html::link($data->contentTitle, $contentRoute, array('title' => $data->contentTitle));
			?>
		<?php endif; ?>
	</td>
<?php echo Html::closeTag('tr'); ?>
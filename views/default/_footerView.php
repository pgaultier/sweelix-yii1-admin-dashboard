<?php
/**
 * File _footerView.php
 *
 * PHP version 5.4+
 *
 * @author    Philippe Gaultier <pgaultier@sweelix.net>
 * @copyright 2010-2014 Sweelix
 * @license   http://www.sweelix.net/license license
 * @version   2.0.1
 * @link      http://www.sweelix.net
 * @category  views
 * @package   sweelix.yii1.admin.dashboard.views.default
 */
?>
<tr>
	<th colspan="3">
		<?php echo(Yii::t('dashboard', $title, $widget->dataProvider->totalItemCount));?>
	</th>
</tr>
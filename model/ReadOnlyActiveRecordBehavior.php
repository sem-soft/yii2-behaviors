<?php
/**
 * @author Самсонов Владимир <samsonov.sem@gmail.com>
 * @copyright Copyright &copy; S.E.M. 2017-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace sem\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Поведение, запрещающее любые модификации данных в рамках AR-модели
 */
class ReadOnlyActiveRecordBehavior extends Behavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
	return [
	    ActiveRecord::EVENT_BEFORE_INSERT => 'denyAll',
	    ActiveRecord::EVENT_BEFORE_UPDATE => 'denyAll',
	    ActiveRecord::EVENT_BEFORE_DELETE => 'denyAll'
	];
    }

    /**
     * Запрещаем любые модификации данных
     * @param \yii\base\ModelEvent $event
     */
    public function denyAll($event)
    {
	$event->isValid = false;
    }

}

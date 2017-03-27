<?php
/**
 * @author Самсонов Владимир <samsonov.sem@gmail.com>
 * @copyright Copyright &copy; S.E.M. 2017-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

namespace sem\filters;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

/**
 * Производит проверку запросов указанных действий на наличие специального
 * заголовка, присущего AJAX-запросу
 */
class AjaxFilter extends Behavior
{

    /**
     * @var [] Перечень действий,
     * которые доупстимо запрашивать только с использованием AJAX
     */
    public $actions;

    /**
     * @var string сообщение, которое отображается пользователю при неудаче
     */
    public $message = "Вызов действия запрещен!";

    /**
     * @inheritDoc
     */
    public function events()
    {
	return [
	    Controller::EVENT_BEFORE_ACTION => 'beforeAction'
	];
    }

    /**
     * Выполняет проверку запросов действий @see self::$actions на использованием AJAX
     * @param \yii\base\Action $event
     * @return boolean
     * @throws BadRequestHttpException если действие запрошено не AJAX-ом
     */
    public function beforeAction($event)
    {
	$action = $event->action->id;
	if (in_array($action, $this->actions) && !Yii::$app->request->isAjax) {
	    $event->isValid = false;
	    throw new BadRequestHttpException($this->message);
	}
	return $event->isValid;
    }

}

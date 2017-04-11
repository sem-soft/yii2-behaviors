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
use yii\helpers\FileHelper;

/**
 * Предоставляет сервис для работы с временным каталогом в рамках приложения.
 * Временный каталог создается перед исполнением действия и удаляется после его исполнения
 * 
 * @property-read strung $tmpDir
 */
class TmpDirControlBehavior extends Behavior
{
    /**
     * Перечень действий, для которых запускать контроль временной директории.
     * Если перечень не задан, то применяется ко всем действиям контроллера
     * @var array 
     */
    public $actions = [];
    
    /**
     * Имя временной директории
     * @var string
     */
    public $tmpDirName = 'tmp';
    
    /**
     * @var string|false
     */
    protected $_tmpDir;

    /**
     * @inheritDoc
     */
    public function events()
    {
	return [
	    Controller::EVENT_BEFORE_ACTION =>	'createTmpDir',
	    Controller::EVENT_AFTER_ACTION  =>	'removeTmpDir'
	];
    }
    
    /**
     * Возвращает путь к временной директори
     * @return string|null
     */
    public function getTmpDir()
    {	
	if (is_null($this->_tmpDir)) {
	    
	    $path = $this->getTempPath();

	    if (file_exists($path)) {
		$this->_tmpDir = $path;
	    }
	    
	}
	
	return $this->_tmpDir;
    }
    
    /**
     * Производит создание темповой директории до начала выполнения действия
     * @param \yii\base\ActionEvent $event
     */
    public function createTmpDir($event)
    {
	// На всякий слечай чистим
	$this->removeTmpDir($event);
	
	if ($this->canControl($event) && !file_exists($this->getTempPath())) {
	    FileHelper::createDirectory($this->getTempPath());
	}
	$event->isValid = true;
	
	return $event->isValid;
	
    }
    
    /**
     * Производит удаление темповой директории после выполнения действия
     * @param \yii\base\ActionEvent $event
     */
    public function removeTmpDir($event)
    {
	if ($this->canControl($event) && file_exists($this->getTempPath())) {
	    FileHelper::removeDirectory($this->getTempPath());
	}
	$event->isValid = true;
	
	return $event->isValid;
    }
    
    /**
     * Проверяет возможность управления временной директорией
     * @param \yii\base\ActionEvent $event
     * @return boolean
     */
    protected function canControl($event)
    {
	return (empty($this->actions) || in_array($event->action->id, $this->actions));
    }
    
    /**
     * Формирует путь к временной директории
     * @return string
     */
    protected function getTempPath()
    {
	return Yii::$app->getRuntimePath() . DIRECTORY_SEPARATOR . $this->tmpDirName;
    }

}

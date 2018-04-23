<?php
/**
 * @author Самсонов Владимир <samsonov.sem@gmail.com>
 * @copyright Copyright &copy; S.E.M. 2018-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace sem\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Поведение производит валидацию и очистку номеров телефонов
 * @package common\behaviors
 */
class PhoneFieldBehavior extends Behavior
{

    /**
     * @var array массив имен атрибутов, для которых производится валидация
     */
    public $attributes;

    /**
     * @var string регулярное выражение - перечень допустимых символов форматирования в номере телефона
     */
    public $formatSymbolsPattern = '\)\(\-\+ ';

    /**
     * @var string регулярное выражение - перечень допустимых цифровых знаков в номере телефона
     */
    public $phoneNumbersPattern = '\d';

    /**
     * @var string сообщение, которое будет показано при неправильном заполнении номера телефона
     */
    public $message;

    /**
     * @var bool сохранять номера телефона в чистом виде без символов форматирования
     */
    public $saveClear = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Номер телефона должен состоять из цифр и символов форматирования: "(", ")", "-", "+"';
        }
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'beforeSave'
        ];
    }

    /**
     * Возвращает полный паттерн для проверки номера телефона со знаками форматирования
     * @return string
     */
    protected function getPattern()
    {
        return "/^[{$this->phoneNumbersPattern}{$this->formatSymbolsPattern}]+$/";
    }

    /**
     * Производит валидацию номерателефона
     */
    public function beforeValidate()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        foreach ($this->attributes as $attribute) {
            if (!preg_match($this->getPattern(), $model->$attribute)) {
                $model->addError($attribute, $this->message);
            }
        }

    }

    /**
     * Если установлен флаг @see $saveClear, то производит очистку телефона от символов форматирования перед сохранением
     */
    public function beforeSave()
    {

        if ($this->saveClear) {
            /** @var ActiveRecord $model */
            $model = $this->owner;

            foreach ($this->attributes as $attribute) {
                $model->$attribute = preg_replace("/[$this->formatSymbolsPattern]/", '', $model->$attribute);
            }
        }

    }
}

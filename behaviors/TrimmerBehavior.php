<?php
/**
 * @author Самсонов Владимир <samsonov.sem@gmail.com>
 * @copyright Copyright &copy; S.E.M. 2017-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace sem\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * Производит обрезку аттрибутов AR-модели с краев по маске символов перед валидацией
 */
class TrimmerBehavior extends Behavior
{

    /**
     * @var string перечень символов, которые будут удалены с краев строки
     */
    protected $_characterMask = " \t\n\r\0\x0B";

    /**
     * @var [] перечень имен атрибутов модели AR, значения которых должны быть обрезаны с краев
     */
    protected $_attributes;

    /**
     * Сеттер @see $_characterMask
     * @param mixed $symbols2trim
     */
    public function setCharacterMask($symbols2trim)
    {
        if ($symbols2trim) {
            $this->_characterMask = $symbols2trim;
        }
    }

    /**
     * Сеттер @see $_attributes
     * 
     * @param [] $attributes2trim
     */
    public function setAttributes($attributes2trim)
    {
        if (!is_array($attributes2trim) || empty($attributes2trim))
            throw new \yii\base\InvalidConfigException("Наименования атрибутов для тримминга должны быть заданы!");
        $this->_attributes = $attributes2trim;
    }

    /**
     * @inheritDoc
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'trimAttrs'
        ];
    }

    /**
     * Последовательно обрезает значения аттрибутов AR-модели с краев по маске символов
     * 
     * @param \yii\base\Event $event
     */
    public function trimAttrs($event)
    {
        foreach ($this->_attributes as $attribute) {
            $this->owner->$attribute = trim($this->owner->$attribute, $this->_characterMask);
        }
    }
}

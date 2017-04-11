# Set of additional behaviors and filters for Yii2 Framework
## Install by composer
composer require sem-soft/yii2-behaviors
## Or add this code into require section of your composer.json and then call composer update in console
"sem-soft/yii2-behaviors": "*"
# Usage
```php
// Model behaviors
  public function behaviors()
  {
    return[
      // Удаляем "точку" перед валидацией
      [
        'class' =>  TrimmerBehavior::className(),
        'attributes'  =>  ['sitemap_priority'],
        'characterMask' =>  '.'
      ]
    ];
	}
  
  // Controller filters
  public function behaviors()
  {
    return [
        // Действие hint запрашивается только AJAX-ом
        'ajax'  =>  [
          'class' => AjaxFilter::className(),
          'actions' => ['hint'],
        ],
    ];
  }
```

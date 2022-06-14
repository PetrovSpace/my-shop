<?php

namespace app\components;

use app\models\Category;
use kartik\sidenav\SideNav;
use yii\base\Widget;

class MenuWidget extends Widget
{

    public $tpl;
    public $ul_class;
    public $data;
    public $tree;
    public $menuHtml;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
//        // get cache
//        $menu = \Yii::$app->cache->get('menu');
//        if($menu){
//            return $menu;
//        }

        $this->data = Category::find()->select('id, parent_id, title')->indexBy('id')->asArray()->all();
        $this->tree = $this->getTree();

        $this->menuHtml .= SideNav::widget([
            'type' => SideNav::TYPE_DEFAULT,
            'heading' => 'Категории',
            'items' => $this->tree,
        ]);

//       // set cache
//        \Yii::$app->cache->set('menu', $this->menuHtml, 60);
        return $this->menuHtml;
    }

    protected function getTree(){
        $tree = [];
        foreach ($this->data as $id => &$node) {
            if (!$node['parent_id']) {
                $tree[$id] = [
                    'url' => '/category/' . $id,
                    'label' => $node['title'],
                ];
            } else {
                $parent = &$tree[$node['parent_id']];
                $children = &$parent['items'][$id];

                $children = [
                    'url' => '/category/' . $id,
                    'label' => $node['title'],
                ];
            }
        }
        return $tree;
    }

}
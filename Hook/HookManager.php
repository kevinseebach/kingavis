<?php
namespace KingAvis\Hook;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\ConfigQuery;

class HookManager extends BaseHook
{

    public function displayKAWidget(HookRenderEvent $event)
    {
				$ka_id = ConfigQuery::create()->findOneByName('tka_marchand_id')->getValue();
				$ka_token = ConfigQuery::create()->findOneByName('tka_marchand_token')->getValue();
				$html = $this->render("tkawidget.html", array("tka_id" => $ka_id, "tka_token" => $ka_token));
				$event->add($html);
    }



}

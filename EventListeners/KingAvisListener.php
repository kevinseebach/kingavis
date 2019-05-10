<?php
/*************************************************************************************/
/*      This file is part of the module Erp                               */
/*                                                                                   */
/*      Copyright (c) Reservoricom                                                     */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace KingAvis\EventListeners;
use \PDO;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Template\ParserInterface;
use Thelia\Model\ConfigQuery;

/**
 * Class KingAvisListener
 * @package KingAvis\EventListeners
 **/
class KingAvisListener extends BaseAction implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
      return [
          TheliaEvents::ORDER_UPDATE_STATUS  => array("sendToKA",4)
      ];
  }


  public function sendToKA(OrderEvent $event)
  {
        $order = $event->getOrder();
        $customer = $order->getCustomer();
        if($order->getStatusId() == ConfigQuery::create()->findOneByName('tka_status_release')->getValue()){
            //STATUS DE DECLENCHEMENT PROCEDER A L'ENVOI

            //init all vars needed
            $ka_id = ConfigQuery::create()->findOneByName('tka_marchand_id')->getValue();
            $ka_token = ConfigQuery::create()->findOneByName('tka_marchand_token')->getValue();
            $ka_pk = ConfigQuery::create()->findOneByName('tka_marchand_private_key')->getValue();
            $t_ref = $order->getRef();
            $t_email = $customer->getEmail();
            $t_amount = $order->getTotalAmount();
            $t_iso_currency = $order->getCurrency()->getCode();
            $t_firstname = $customer->getFirstname();
            $t_lastname = $customer->getLastname();
            $t_iso_lang = $customer->getCustomerLang()->getCode();
            //proceed send
            $curl = curl_init();
            if(empty($ka_id) || empty($ka_token) || empty($ka_pk) ){
                throw new \RuntimeException("you must configure your King Avis access ");
            }
            if(empty($t_ref) || empty($t_email) || empty($t_amount) || empty($t_iso_currency) || empty($t_firstname) || empty($t_lastname) || empty($t_iso_lang) ){
                throw new \RuntimeException("Error when getting order informations ");
            }
            $ka_url = "https://king-avis.com/fr/merchantorder/add?id_merchant=".$ka_id."&token=".$ka_token."&private_key=".$ka_pk."&ref_order=".$t_ref."&email=".$t_email."&amount=".$t_amount."&iso_currency=".$t_iso_currency."&firstname=".urlencode($t_firstname)."&lastname=".urlencode($t_lastname)."&iso_lang=".$t_iso_lang;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $ka_url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);
            curl_close($curl);

            if($result != "OK"){
                throw new \RuntimeException("Error sending order informations ");
            }
        }
  }


}

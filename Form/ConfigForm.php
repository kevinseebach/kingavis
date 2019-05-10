<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace KingAvis\Form;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\ConfigQuery;
use KingAvis\KingAvis;
/**
 * Class ConfigForm
 * @package Yot\Form
 * @author Manuel Raynaud <manu@thelia.net>
 */
class ConfigForm extends BaseForm
{

    protected function buildForm()
    {
        $translator = Translator::getInstance();
        $this->formBuilder
        ->add(
            'marchand_id',
            'text',
            [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => "Votre ID marchand",
                'data' => ConfigQuery::read('tka_marchand_id')
            ]
        )
        ->add(
            'marchand_token',
            'text',
            [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => "Votre token marchand",
                'data' => ConfigQuery::read('tka_marchand_token')
            ]
        )
        ->add(
            'marchand_private_key',
            'text',
            [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => "Votre clef privée KingAvis",
                'data' => ConfigQuery::read('tka_marchand_private_key')
            ]
        )
        ->add(
            'status_release',
            'number',
            [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => "Status déclanchant l'envoi",
                'data' => ConfigQuery::read('tka_status_release')
            ]
        )
        ;
    }
    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'KingAvis_config';
    }
}

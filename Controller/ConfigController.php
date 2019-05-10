<?php
namespace KingAvis\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;
use Thelia\Model\ConfigQuery;
use KingAvis\Form\ConfigForm;
use KingAvis\KingAvis;
/**
 * Class ConfigController
 * @package KingAvis\Controller
 * @author Manuel Raynaud <manu@thelia.net>
 */
class ConfigController extends BaseAdminController
{
    public function saveAction()
    {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, ['KingAvis'], AccessManager::UPDATE)) {
            return $response;
        }
        $form = new ConfigForm($this->getRequest());
        $errorMessage = null;
        $response = null;
        try {
            $configForm = $this->validateForm($form);
            ConfigQuery::write('tka_marchand_id', $configForm->get('marchand_id')->getData(), 1, 1);
            ConfigQuery::write('tka_marchand_token', $configForm->get('marchand_token')->getData(), 1, 1);
            ConfigQuery::write('tka_marchand_private_key', $configForm->get('marchand_private_key')->getData(), 1, 1);
            ConfigQuery::write('tka_status_release', $configForm->get('status_release')->getData(), 1, 1);
            $response = RedirectResponse::create(URL::getInstance()->absoluteUrl('/admin/module/KingAvis'));
        } catch (FormValidationException $e) {
            $errorMessage = $e->getMessage();
        }
        if (null !== $errorMessage) {
            $this->setupFormErrorContext(
                'KingAvis config fail',
                $errorMessage,
                $form
            );
            $response = $this->render(
                "module-configure",
                [
                    'module_code' => 'KingAvis'
                ]
            );
        }
        return $response;
    }
}

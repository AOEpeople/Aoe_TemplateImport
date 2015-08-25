<?php

/**
 * Origin admin controller
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Adminhtml_Templateimport_OriginController extends Aoe_TemplateImport_Controller_Adminhtml_TemplateImport
{
    /**
     * init the origin
     *
     * @access protected
     * @return Aoe_TemplateImport_Model_Origin
     */
    protected function _initOrigin()
    {
        $originId  = (int) $this->getRequest()->getParam('id');
        $origin    = Mage::getModel('aoe_templateimport/origin');
        if ($originId) {
            $origin->load($originId);
        }
        Mage::register('current_origin', $origin);
        return $origin;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('aoe_templateimport')->__('AOE Template Import'))
             ->_title(Mage::helper('aoe_templateimport')->__('Origins'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit origin - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $originId    = $this->getRequest()->getParam('id');
        $origin      = $this->_initOrigin();
        if ($originId && !$origin->getId()) {
            $this->_getSession()->addError(
                Mage::helper('aoe_templateimport')->__('This origin no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getOriginData(true);
        if (!empty($data)) {
            $origin->setData($data);
        }
        Mage::register('origin_data', $origin);
        $this->loadLayout();
        $this->_title(Mage::helper('aoe_templateimport')->__('AOE Template Import'))
             ->_title(Mage::helper('aoe_templateimport')->__('Origins'));
        if ($origin->getId()) {
            $this->_title($origin->getFullActionName());
        } else {
            $this->_title(Mage::helper('aoe_templateimport')->__('Add origin'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new origin action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save origin - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('origin')) {
            try {
                $origin = $this->_initOrigin();
                $origin->addData($data);
                $origin->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_templateimport')->__('Origin was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $origin->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setOriginData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was a problem saving the origin.')
                );
                Mage::getSingleton('adminhtml/session')->setOriginData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('aoe_templateimport')->__('Unable to find origin to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete origin - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $origin = Mage::getModel('aoe_templateimport/origin');
                $origin->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_templateimport')->__('Origin was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was an error deleting origin.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('aoe_templateimport')->__('Could not find origin to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete origin - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $originIds = $this->getRequest()->getParam('origin');
        if (!is_array($originIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_templateimport')->__('Please select origins to delete.')
            );
        } else {
            try {
                foreach ($originIds as $originId) {
                    $origin = Mage::getModel('aoe_templateimport/origin');
                    $origin->setId($originId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_templateimport')->__('Total of %d origins were successfully deleted.', count($originIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was an error deleting origins.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $originIds = $this->getRequest()->getParam('origin');
        if (!is_array($originIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_templateimport')->__('Please select origins.')
            );
        } else {
            try {
                foreach ($originIds as $originId) {
                $origin = Mage::getSingleton('aoe_templateimport/origin')->load($originId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d origins were successfully updated.', count($originIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was an error updating origins.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'origin.csv';
        $content    = $this->getLayout()->createBlock('aoe_templateimport/adminhtml_origin_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'origin.xls';
        $content    = $this->getLayout()->createBlock('aoe_templateimport/adminhtml_origin_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'origin.xml';
        $content    = $this->getLayout()->createBlock('aoe_templateimport/adminhtml_origin_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/aoe_templateimport/origin');
    }
}

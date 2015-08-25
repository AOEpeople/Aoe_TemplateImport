<?php
/**
 * Aoe_TemplateImport extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Aoe
 * @package        Aoe_TemplateImport
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Template admin controller
 *
 * @category    Aoe
 * @package     Aoe_TemplateImport
 * @author      Ultimate Module Creator
 */
class Aoe_TemplateImport_Adminhtml_Templateimport_TemplateController extends Aoe_TemplateImport_Controller_Adminhtml_TemplateImport
{
    /**
     * init the template
     *
     * @access protected
     * @return Aoe_TemplateImport_Model_Template
     */
    protected function _initTemplate()
    {
        $templateId  = (int) $this->getRequest()->getParam('id');
        $template    = Mage::getModel('aoe_templateimport/template');
        if ($templateId) {
            $template->load($templateId);
        }
        Mage::register('current_template', $template);
        return $template;
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
             ->_title(Mage::helper('aoe_templateimport')->__('Templates'));
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
     * edit template - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $templateId    = $this->getRequest()->getParam('id');
        $template      = $this->_initTemplate();
        if ($templateId && !$template->getId()) {
            $this->_getSession()->addError(
                Mage::helper('aoe_templateimport')->__('This template no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTemplateData(true);
        if (!empty($data)) {
            $template->setData($data);
        }
        Mage::register('template_data', $template);
        $this->loadLayout();
        $this->_title(Mage::helper('aoe_templateimport')->__('AOE Template Import'))
             ->_title(Mage::helper('aoe_templateimport')->__('Templates'));
        if ($template->getId()) {
            $this->_title($template->getFullActionName());
        } else {
            $this->_title(Mage::helper('aoe_templateimport')->__('Add template'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new template action
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
     * save template - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('template')) {
            try {
                $template = $this->_initTemplate();
                $template->addData($data);
                $template->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_templateimport')->__('Template was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $template->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTemplateData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was a problem saving the template.')
                );
                Mage::getSingleton('adminhtml/session')->setTemplateData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('aoe_templateimport')->__('Unable to find template to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete template - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $template = Mage::getModel('aoe_templateimport/template');
                $template->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_templateimport')->__('Template was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was an error deleting template.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('aoe_templateimport')->__('Could not find template to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete template - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $templateIds = $this->getRequest()->getParam('template');
        if (!is_array($templateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_templateimport')->__('Please select templates to delete.')
            );
        } else {
            try {
                foreach ($templateIds as $templateId) {
                    $template = Mage::getModel('aoe_templateimport/template');
                    $template->setId($templateId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('aoe_templateimport')->__('Total of %d templates were successfully deleted.', count($templateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was an error deleting templates.')
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
        $templateIds = $this->getRequest()->getParam('template');
        if (!is_array($templateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('aoe_templateimport')->__('Please select templates.')
            );
        } else {
            try {
                foreach ($templateIds as $templateId) {
                $template = Mage::getSingleton('aoe_templateimport/template')->load($templateId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d templates were successfully updated.', count($templateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('aoe_templateimport')->__('There was an error updating templates.')
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
        $fileName   = 'template.csv';
        $content    = $this->getLayout()->createBlock('aoe_templateimport/adminhtml_template_grid')
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
        $fileName   = 'template.xls';
        $content    = $this->getLayout()->createBlock('aoe_templateimport/adminhtml_template_grid')
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
        $fileName   = 'template.xml';
        $content    = $this->getLayout()->createBlock('aoe_templateimport/adminhtml_template_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('system/aoe_templateimport/template');
    }
}

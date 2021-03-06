<?php

/**
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
class DiamanteDesk_Config extends DiamanteDesk
{
    public function getContent()
    {
        $output = null;
        if (Tools::isSubmit('submit')) {
            Configuration::updateValue('DIAMANTEDESK_SERVER_ADDRESS', Tools::getValue('DIAMANTEDESK_SERVER_ADDRESS'));
            Configuration::updateValue('DIAMANTEDESK_USERNAME', Tools::getValue('DIAMANTEDESK_USERNAME'));
            Configuration::updateValue('DIAMANTEDESK_API_KEY', Tools::getValue('DIAMANTEDESK_API_KEY'));
            Configuration::updateValue('DIAMANTEDESK_DEFAULT_BRANCH', Tools::getValue('DIAMANTEDESK_DEFAULT_BRANCH'));
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output . $this->renderForm();
    }


    public function renderForm()
    {

        $branches = getDiamanteDeskApi()->getBranches();

        $options = array(
            array(
                'id_option' => 0,
                'name' => 'All'
            ),
        );

        if ($branches) {
            foreach ($branches as $branch) {
                $options[] = array(
                    'id_option' => $branch->id,
                    'name' => $branch->name
                );
            }
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('DiamanteDesk Settings'),
                    'icon' => 'icon-AdminDiamanteDeskDark'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Server Address'),
                        'name' => 'DIAMANTEDESK_SERVER_ADDRESS',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Username'),
                        'name' => 'DIAMANTEDESK_USERNAME',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Api Key'),
                        'name' => 'DIAMANTEDESK_API_KEY',
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        if ($branches) {
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'lang' => true,
                'label' => $this->l('Default Branch'),
                'name' => 'DIAMANTEDESK_DEFAULT_BRANCH',
                'options' => array(
                    'query' => $options,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            );
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 1;
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'DIAMANTEDESK_SERVER_ADDRESS' => Tools::getValue('DIAMANTEDESK_SERVER_ADDRESS', Configuration::get('DIAMANTEDESK_SERVER_ADDRESS')),
            'DIAMANTEDESK_USERNAME' => Tools::getValue('DIAMANTEDESK_USERNAME', Configuration::get('DIAMANTEDESK_USERNAME')),
            'DIAMANTEDESK_API_KEY' => Tools::getValue('DIAMANTEDESK_API_KEY', Configuration::get('DIAMANTEDESK_API_KEY')),
            'DIAMANTEDESK_DEFAULT_BRANCH' => Tools::getValue('DIAMANTEDESK_DEFAULT_BRANCH', Configuration::get('DIAMANTEDESK_DEFAULT_BRANCH')),
        );
    }

}
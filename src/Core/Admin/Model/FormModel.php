<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Admin\Model;

use Exception;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;

class FormModel
{
    private array $form_data = [];

    public function __construct(FormView $formView)
    {
        /** @var FormView $formChildren */
        foreach ($formView->children as $formLabel => $formChildren) {
            $formVars = $formChildren->vars;
            $formType = $formVars['block_prefixes'][1];

            $formChoices = [];

            if ('choice' === $formType) {
                /** @var ChoiceView $choice */
                foreach ($formVars['choices'] as $key => $choice) {
                    $formChoices[] = [
                        'label' => $choice->label,
                        'value' => $choice->value,
                        'data'  => $choice->data,
                        'key'   => $key
                    ];
                }
            }

            $formChildrens = [];
            $formPrototype = [];
            if ('collection' === $formType) {
                foreach ($formVars['data'] as $key => $children) {
                    $formChoices[] = [
                        'label' => 'test',
                        'key'   => $key
                    ];
                }

                foreach ($formVars['prototype'] as $key => $prototype) {
                    $formPrototype[] = [
                        'label' => $prototype->vars['label'],
                        'value' => $prototype->vars['value'],
                        'data'  => $prototype->vars['data'],
                        'key'   => $key
                    ];
                }
            }

            $this->form_data[$formLabel] = [
                'name'              => $formVars['name'],
                'value'             => $formVars['value'],
                'type'              => $formType,
                'id'                => $formVars['id'],
                'choice_values'     => $formChoices,
                'collection_values' => $formChildrens,
                'prototype'         => $formPrototype,
            ];
        }
    }

    /**
     * @throws Exception
     */
    public function getFormDataArray(): array
    {
        if (!empty($this->form_data)) {
            return $this->form_data;
        } else {
            throw new Exception('The form has no childs');
        }
    }
}

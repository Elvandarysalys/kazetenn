<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\ContentBuilder\Model;

use Exception;
use Kazetenn\Pages\Entity\PageContent;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;

class FormModel
{
    private array $form_data = [];

    private function contentToArray(PageContent $pageContent){
        $result = [
            'id' => $pageContent->getId()->toRfc4122(),
            'content' => $pageContent->getContent(),
            'template' => $pageContent->getTemplate(),
            'blocOrder' => $pageContent->getBlocOrder(),
            'align' => $pageContent->getAlign(),
            'childrens' => []
        ];

        if (!empty($pageContent->getContent())){
            foreach ($pageContent->getChildrens() as $children){
                $result['childrens'][] = $this->contentToArray($children);
            }
        }

        return $result;
    }

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
                    $formChildrens[] = $this->contentToArray($children);
                }

                foreach ($formVars['prototype'] as $key => $prototype) {
                    $formPrototype[] = [
                        'label' => $prototype->vars['label'],
                        'value' => $prototype->vars['value'],
                        'data'  => $prototype->vars['data'],
                        'key'   => $key
                    ];
                }

                $this->form_data[$formLabel] = [
                    'name'              => $formVars['name'],
                    'value'             => $formChildrens,
                    'type'              => $formType,
                    'id'                => $formVars['id'],
                    'choice_values'     => $formChoices,
                    'collection_values' => $formChildrens,
                    'prototype'         => $formPrototype,
                ];
            }else{
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

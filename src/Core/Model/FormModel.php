<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Core\Model;

use Exception;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormView;

class FormModel
{
    private array $form_data = [];

    private function contentToArray(FormView $pageContent): array
    {
        $result = [];
        foreach ($pageContent->children as $name => $data) {
            $formVars = $data->vars;
            $formType = $formVars['block_prefixes'][1];

            if ('collection' === $formType) {
                $formChild = $this->contentToArray($data);

                $result[$name] = [
                    'name'              => $formVars['name'],
                    'value'             => $formChild,
                    'type'              => $formType,
                    'id'                => $formVars['id'],
                    'choice_values'     => null,
                    'collection_values' => $formChild,
                ];
            } else {
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

                $result[$name] = [
                    'name'              => $formVars['name'],
                    'value'             => $formVars['value'],
                    'type'              => $formType,
                    'id'                => $formVars['id'],
                    'choice_values'     => $formChoices,
                    'collection_values' => null,
                ];
            }
        }

        return $result;
    }

    function buildChilds(FormView $formView): array
    {
        $formChildren = [];

        foreach ($formView->children as $children) {
            $formChildren[] = $this->contentToArray($children);
        }

        return $formChildren;
    }

    public function __construct(FormView $formView)
    {
        /** @var FormView $formChildren */
        foreach ($formView->children as $formLabel => $formChildren) {
            $formVars = $formChildren->vars;
            $formType = $formVars['block_prefixes'][1];

            if ('collection' === $formType) {
                $formChild = $this->buildChilds($formChildren);

                $this->form_data[$formLabel] = [
                    'name'              => $formVars['name'],
                    'value'             => $formChild,
                    'type'              => $formType,
                    'id'                => $formVars['id'],
                    'choice_values'     => null,
                    'collection_values' => $formChild,
                ];
            } else {
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

                $this->form_data[$formLabel] = [
                    'name'              => $formVars['name'],
                    'value'             => $formVars['value'],
                    'type'              => $formType,
                    'id'                => $formVars['id'],
                    'choice_values'     => $formChoices,
                    'collection_values' => null,
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

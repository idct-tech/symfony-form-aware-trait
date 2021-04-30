<?php

namespace IDCT\Symfony\Form;

use Symfony\Component\Form\FormInterface;

trait FormAwareTrait
{
    protected function getErrorsFromForm(FormInterface $form, int $level = 0)
    {
        $errors = [];
        if ($level === 0) {
            foreach ($form->getErrors() as $error) {
                if (!isset($errors['form'])) {
                    $errors['form'] = [];
                }
                $errors['form'][] = $error->getMessage();
            }
        } else {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm, $level + 1)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        $suberrors = [];
        if (isset($errors['first'])) {
            $suberrors = $errors['first'];
        }

        if (isset($errors['second'])) {
            $suberrors = array_merge($suberrors, $errors['second']);
        }

        if (!empty($suberrors)) {
            $errors = $suberrors;
        }

        return $errors;
    }

    protected function flatten(array $array)
    {
        $result = [];
        foreach ($array as $key => $values) {
            $result[$key] = join(' ', $values);
        }

        return $result;
    }
}

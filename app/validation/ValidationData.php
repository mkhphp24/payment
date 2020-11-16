<?php

namespace App\validation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ValidationData
{
    private $requestData;
    private $validateManager;

    public function __construct(array $requestData)
    {
        $this->requestData = $requestData;
        $this->validateManager = Validation::createValidator();
    }

    /**
     * @param $constraint
     * @param $groups
     *
     * @return array[]
     */
    protected function setValidate($constraint, $groups)
    {
        $messageError = [];
        $values = [];
        $violations = $this->validateManager->validate($this->requestData, $constraint, $groups);

        foreach ($violations as $violation) {
            $messageError[] = array('nameProperty' => $violation->getPropertyPath(), 'message' => $violation->getMessage(), 'value' => $violation->getInvalidValue());
        }

        return ['Error' => $messageError, 'values' => $values];
    }

    /**
     * @return array
     */
    public function validateStep1(): array
    {
        $groups     = new Assert\GroupSequence(['Default', 'custom']);
        $constraint = new Assert\Collection([
              'firstname' => [new Assert\NotBlank(), new Assert\Type(['type'=>['string']]) ]
            ,  'lastname' => [ new Assert\NotBlank(),new Assert\Type(['type'=>['string']]) ]
            ,  'telephone' => [ new Assert\NotBlank(),new Assert\Type(['type'=>['string']]) ]
        ]);

        return $this->setValidate($constraint, $groups);

    }
    /**
     * @return array
     */
    public function validateStep2(): array
    {
        $groups     = new Assert\GroupSequence(['Default', 'custom']);
        $constraint = new Assert\Collection([
               'street' => [new Assert\NotBlank(), new Assert\Type(['type'=>['string']]) ]
            ,  'house_number' => [ new Assert\NotBlank(),new Assert\Type(['type'=>['digit']]) ]
            ,  'zip' => [ new Assert\NotBlank(),new Assert\Type(['type'=>['digit']]) ]
            ,  'city' => [ new Assert\NotBlank(),new Assert\Type(['type'=>['string']]) ]
        ]);

        return $this->setValidate($constraint, $groups);

    }

    /**
     * @return array
     */
    public function validateStep3(): array
    {
        $groups     = new Assert\GroupSequence(['Default', 'custom']);
        $constraint = new Assert\Collection([
            'iban' => [new Assert\NotBlank(), new Assert\Type(['type'=>['string']]) ] ,
            'account_owner' => [new Assert\NotBlank(), new Assert\Type(['type'=>['string']]) ]
        ]);

        return $this->setValidate($constraint, $groups);

    }

    /**
     * @param array $validation
     * @return array
     */
    public function setTwigError(array $validation){
        $result=[];
        foreach ( $validation as $value) {
            $nameField=substr($value['nameProperty'],1,-1);
            @$result[$nameField]['error'].="<li>".$value['message']."</li>";
        }
        return $result;
    }



}

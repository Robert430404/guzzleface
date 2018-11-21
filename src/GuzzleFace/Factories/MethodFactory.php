<?php

namespace Robert430404\GuzzleFace\Factories;

use Memio\Model\Argument;
use Memio\Model\Method;
use Robert430404\GuzzleFace\Annotations\Action;
use Robert430404\GuzzleFace\Enumerations\BodyEnumerations;

/**
 * Class MethodFactory
 *
 * @package Robert430404\GuzzleFace\Factories
 */
class MethodFactory
{
    /**
     * @var \ReflectionMethod[]
     */
    private $reflectionMethods = [];

    /**
     * @var array
     */
    private $annotations = [];

    /**
     * MethodFactory constructor.
     *
     * @param array $methods
     * @param array $annotations
     */
    public function __construct(array $methods, array $annotations)
    {
        $this->reflectionMethods = $methods;
        $this->annotations = $annotations;
    }

    /**
     * @return Method[]
     *
     * @throws \Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException
     */
    public function makeMethods(): array
    {
        $methods[] = (new Method('__construct'))
            ->addArgument(new Argument('array', 'config = []'))
            ->setBody('parent::__construct($config);');

        foreach ($this->reflectionMethods as $key => $reflectionMethod) {
            $requestOptions = [];

            /** @var Action $action */
            $action = array_reduce($this->annotations[$key], function ($carry, $annotation) {
                return ($annotation instanceof Action) ? $annotation : $carry;
            });

            $classMethod = new Method($reflectionMethod->getName());
            $httpMethod = $action->getMethod();
            $httpEndpoint = $action->getEndpoint();

            $classMethod->setReturnType(
                $reflectionMethod->getReturnType()->getName()
            );

            foreach ($reflectionMethod->getParameters() as $parameter) {
                $classMethod->addArgument(
                    new Argument(
                        $parameter->getType()->getName(),
                        $parameter->getName()
                    )
                );
            }

            if ($action->hasBody()) {
                switch ($action->getBodyType()) {
                    case BodyEnumerations::JSON_BODY :
                        $requestOptions['body'] = '$body';
                        break;
                    case BodyEnumerations::MULTI_PART_BODY :
                        $requestOptions['multipart'] = '$body';
                        break;
                    case BodyEnumerations::FORM_PARAMS_BODY :
                        $requestOptions['form_params'] = '$body';
                        break;
                }
            }

            $stringOptions = $this->stringifyOptions($requestOptions ?? []);

            $methods[] = $classMethod->setBody(
                "return \$this->request('{$httpMethod}', '{$httpEndpoint}', {$stringOptions});"
            );
        }

        return $methods;
    }

    /**
     * Converts the request options from an array to a string for use in the code generator.
     *
     * @param array $options
     *
     * @return string
     */
    private function stringifyOptions(array $options): string
    {
        // Convert the array into a valid php expression for later parsing.
        $stringify = var_export($options, true);

        // Convert vars from string literals to there non literal counterparts.
        $stringify = preg_replace('/(\')(\$[a-zA-Z]*)(\')/', '$2', $stringify);

        // Remove all of the line breaks from the generated code.
        $stringify = preg_replace('/\r?\n|\r/', '', $stringify);

        // Remove the spaces from the generated options.
        $stringify = str_replace(' ', '', $stringify);

        return $stringify;
    }
}
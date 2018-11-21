<?php

namespace Robert430404\GuzzleFace\Factories;

use Memio\Model\Argument;
use Memio\Model\Method;
use Robert430404\GuzzleFace\Annotations\Action;
use Robert430404\GuzzleFace\Annotations\Request\Headers;
use Robert430404\GuzzleFace\Enumerations\BodyEnumerations;
use Robert430404\GuzzleFace\Enumerations\ConfigurationEnumerations;

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
            ->setBody(
                $this->padBody('parent::__construct($config);')
            );

        foreach ($this->reflectionMethods as $key => $reflectionMethod) {
            $requestOptions = [];
            $requestBody = [];
            $requestHeaders = [];

            /** @var Action $action */
            $action = array_reduce($this->annotations[$key], function ($carry, $annotation) {
                return ($annotation instanceof Action) ? $annotation : $carry;
            });

            if ($action !== null) {
                $requestBody = $this->buildRequestBody($action);
            }

            /** @var Headers $headers */
            $headers = array_reduce($this->annotations[$key], function ($carry, $annotation) {
                return ($annotation instanceof Headers) ? $annotation : $carry;
            });

            if ($headers !== null) {
                $requestHeaders = $this->buildRequestHeaders($headers);
            }

            $classMethod = new Method($reflectionMethod->getName());

            $classMethod->setReturnType(
                $reflectionMethod->getReturnType()->getName()
            );

            $this->buildParameters($classMethod, $reflectionMethod->getParameters());

            // Merge all of the different request options together.
            $requestOptions = array_merge(
                $requestOptions,
                $requestBody,
                $requestHeaders
            );

            // Stringify the options for use in the method body.
            $stringOptions = $this->stringifyOptions($requestOptions);

            $methods[] = $classMethod->setBody(
                $this->padBody(
                    "return \$this->request('{$action->getMethod()}', '{$action->getEndpoint()}', {$stringOptions});"
                )
            );
        }

        return $methods;
    }

    /**
     * Builds the parameters for the method.
     *
     * @param Method $classMethod
     * @param \ReflectionParameter[] $reflectionParameters
     *
     * @return void
     */
    private function buildParameters(Method $classMethod, array $reflectionParameters): void
    {
        foreach ($reflectionParameters as $parameter) {
            $classMethod->addArgument(
                new Argument(
                    $parameter->getType()->getName(),
                    $parameter->getName()
                )
            );
        }
    }

    /**
     * Builds the request body from the action.
     *
     * @param Action $action
     *
     * @return array
     *
     * @throws \Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException
     */
    private function buildRequestBody(Action $action): array
    {
        if (!$action->hasBody()) {
            return [];
        }

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

        return $requestOptions ?? [];
    }

    /**
     * Builds the array of request headers.
     *
     * @param Headers $headers
     *
     * @return array
     */
    private function buildRequestHeaders(Headers $headers): array
    {
        $headerBag = [];

        if (!$headers->hasHeaders()) {
            return $headerBag;
        }

        foreach ($headers->getHeaders() as $header) {
            $headerBag[] = $header->getValue();
        }

        return [ConfigurationEnumerations::HEADER_CONFIG_KEY => $headerBag];
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

        // remove index from non-associative arrays
        $stringify = preg_replace('/\d+ => /', '', $stringify);

        // Remove all of the line breaks from the generated code.
        $stringify = preg_replace('/\r?\n|\r/', '', $stringify);

        // Remove the spaces from the generated options.
        $stringify = str_replace(' ', '', $stringify);

        // Convert to short array syntax
        // TODO: Find better way of doing this conversion.
        $stringify = str_replace('array()', '[]', $stringify);
        $stringify = str_replace('array(', '[', $stringify);
        $stringify = str_replace(',)', ',]', $stringify);

        return $stringify;
    }

    /**
     * Handles padding the string for the indentation of the generated code.
     *
     * @param string $body
     * @param int $amount
     *
     * @return string
     */
    private function padBody(string $body, int $amount = 8): string
    {
        return str_pad(
            $body,
            strlen($body) + $amount,
            " ",
            STR_PAD_LEFT
        );
    }
}
<?php

namespace Robert430404\GuzzleFace\Factories;

use GuzzleHttp\Exception\GuzzleException;
use Memio\Model\Argument;
use Memio\Model\Method;
use Memio\Model\Phpdoc\Description;
use Memio\Model\Phpdoc\MethodPhpdoc;
use Memio\Model\Phpdoc\ParameterTag;
use Memio\Model\Phpdoc\ReturnTag;
use Memio\Model\Phpdoc\ThrowTag;
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
     * These are the various regex used for parsing the generated code.
     *
     * @const string
     */
    const NEWLINE_REGEX = '/' . PHP_EOL . '\s*' . PHP_EOL . '/';
    const ARRAY_INDENTATION_REGEX = '/(=>)([ ])(\r|\n)([ ]*)/';
    const VAR_CONVERSION_REGEX = '/(\')(\$[a-zA-Z]*)(\')/';
    const ARRAY_INDEX_REGEX = '/\d+ => /';

    /**
     * @var \ReflectionMethod[]
     */
    private $reflectionMethods = [];

    /**
     * @var array
     */
    private $annotations = [];

    /**
     * @var array
     */
    private $clientConfig = [];

    /**
     * MethodFactory constructor.
     *
     * @param array $methods
     * @param array $annotations
     * @param array $clientConfig
     */
    public function __construct(
        array $methods,
        array $annotations,
        array $clientConfig
    ) {
        $this->reflectionMethods = $methods;
        $this->annotations = $annotations;
        $this->clientConfig = $clientConfig;
    }

    /**
     * @return Method[]
     *
     * @throws \Robert430404\GuzzleFace\Exceptions\NoBodyTypeProvidedException
     */
    public function makeMethods(): array
    {
        $stringClientConfig = $this->stringifyOptions($this->clientConfig);

        $methods[] = (new Method('__construct'))
            ->setPhpdoc(
                (new MethodPhpdoc())
                    ->setDescription(
                        (new Description('Creates new client instance.'))
                    )
                    ->addParameterTag(
                        (new ParameterTag('array', 'config', 'client configuration'))
                    )
            )
            ->addArgument(new Argument('array', 'config = []'))
            ->setBody(
                $this->padBody("parent::__construct(array_merge({$stringClientConfig}, \$config));")
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

            $methodDoc = (new MethodPhpdoc())
                ->setDescription(
                    (new Description("Performs the {$reflectionMethod->getName()} call"))
                )
                ->addThrowTag(
                    (new ThrowTag('\\' . GuzzleException::class))
                )
                ->setReturnTag(
                    new ReturnTag(
                        '\\' . $reflectionMethod->getReturnType()->getName()
                    )
                );

            $classMethod = (new Method($reflectionMethod->getName()))
                ->setReturnType(
                    $reflectionMethod->getReturnType()->getName()
                );

            $this->buildParameters($classMethod, $reflectionMethod->getParameters(), $methodDoc);

            // Merge all of the different request options together.
            $requestOptions = array_merge(
                $requestOptions,
                $requestBody,
                $requestHeaders
            );

            // Stringify the options for use in the method body.
            $stringOptions = $this->stringifyOptions($requestOptions);

            $methods[] = $classMethod->setPhpdoc($methodDoc)->setBody(
                $this->padBody(
                    "return \$this->request('{$action->getMethod()}', \"{$action->getEndpoint()}\", {$stringOptions});"
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
    private function buildParameters(Method $classMethod, array $reflectionParameters, MethodPhpdoc $methodDoc): void
    {
        foreach ($reflectionParameters as $parameter) {
            $methodDoc->addParameterTag(
                new ParameterTag(
                    $parameter->getType()->getName(),
                    $parameter->getName()
                )
            );

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
                $requestOptions['body'] = "\${$action->getBodyParamName()}";
                break;
            case BodyEnumerations::MULTI_PART_BODY :
                $requestOptions['multipart'] = "\${$action->getBodyParamName()}";
                break;
            case BodyEnumerations::FORM_PARAMS_BODY :
                $requestOptions['form_params'] = "\${$action->getBodyParamName()}";
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

        foreach ($headers->getHeaders() as $header) {
            if ($header instanceof Headers\AuthBasic) {
                $headerBag['Authorization'] = 'Basic ' . base64_encode(implode(':', $header->getValue()));
                continue;
            }

            $headerBag = array_merge($header->getValue(), $headerBag);
        }

        return [ConfigurationEnumerations::HEADER_CONFIG_KEY => $headerBag];
    }

    /**
     * Converts the request options from an array to a string for use in the code generator.
     *
     * TODO: Find a way to convert to short syntax
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
        $stringify = preg_replace(static::VAR_CONVERSION_REGEX, '$2', $stringify);

        // remove index from non-associative arrays
        $stringify = preg_replace(static::ARRAY_INDEX_REGEX, '', $stringify);

        // Remove doubled line breaks
        $stringify = preg_replace(static::NEWLINE_REGEX, PHP_EOL, $stringify);

        // Setup multi-line formatting for the generated options.
        $stringify = str_replace(' ', '  ', $stringify);
        $stringify = str_replace('Bearer  ', 'Bearer ', $stringify);
        $stringify = str_replace('Basic  ', 'Basic ', $stringify);
        $stringify = str_replace('  =>  ', ' => ', $stringify);
        $stringify = str_replace('array  (', 'array(', $stringify);
        $stringify = $this->padMultiLineString($stringify);
        $stringify = preg_replace(static::ARRAY_INDENTATION_REGEX, '=> ', $stringify);

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

    /**
     * Handles padding a multi line string for the generated code.
     *
     * @param string $body
     * @param int $amount
     *
     * @return string
     */
    private function padMultiLineString(string $body, int $amount = 8): string
    {
        $strings = explode(PHP_EOL, $body);

        foreach ($strings as $key => $string) {
            if ($key === 0) { // Don't pad the array opening
                continue;
            }

            $strings[$key] = $this->padBody($string);
        }

        return implode(PHP_EOL, $strings);
    }
}
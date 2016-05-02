<?php

namespace Rootwork\Phalcon\Mvc\View;

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Exception;

/**
 * View String Renderer
 *
 * @copyright   Copyright (c) 2016 Rootwork InfoTech LLC (www.rootwork.it)
 * @license     BSD-3-clause
 * @author      Mike Soule <mike@rootwork.it>
 * @package     Rootwork\Phalcon\Mvc\View
 */
class Stringable extends View
{

    /**
     * Render a string template and return the content.
     *
     * @param string        $template
     * @param array|null    $params
     *
     * @return string The rendered template
     * @throws Exception
     */
    public function renderString($template, array $params = null)
    {
        $this->compileString($template, $params);

        return $this->getRender($this->_pickView[0], $this->_pickView[1], $params);
    }

    /**
     * Compile a string template and return the compiled template.
     *
     * @param string        $template
     * @param array|null    $params
     *
     * @return string The path to the compiled string.
     * @throws Exception
     */
    public function compileString($template, array $params = null)
    {
        if (!empty($params)) {
            $this->setVars($params);
        }

        foreach ($this->getRegisteredEngines() as $extension => $engine) {
            if ($extension == 'string' && method_exists($engine, 'getCompiler')) {
                /** @var View\Engine\Volt $engine */
                $compiler   = $engine->getCompiler();
                $compiled   = $compiler->compileString($template);
                $compileExt = $this->getStringCompileExtension();
                $filename   = tempnam($this->getStringCompilePath(), 'tmp') . $compileExt;

                if (file_put_contents($filename, $compiled) === false) {
                    throw new Exception("Unable to compile template string to '$filename'");
                }

                $this->pick([dirname($filename), basename($filename, $compileExt)]);

                return $filename;
            }
        }

        throw new Exception('No engine configured for rendering string templates');
    }

    /**
     * Get the string compile path.
     *
     * @return string
     */
    public function getStringCompilePath()
    {
        $path = $this->getViewsDir() . 'string-compile';

        if (isset($this->_options['stringCompilePath'])) {
            $path = $this->_options['stringCompilePath'];
        }

        if (substr($path, -1) != DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    /**
     * Get the string compile extension.
     *
     * @return string
     */
    public function getStringCompileExtension()
    {
        if (isset($this->_options['stringCompileExt'])) {
            return $this->_options['stringCompileExt'];
        }

        return '.phtml';
    }
}

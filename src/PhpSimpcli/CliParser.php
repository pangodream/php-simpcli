<?php
/**
 * Created by Pangodream.
 * Date: 01/11/2018
 * Time: 10:49
 */

namespace PhpSimpcli;
use \stdClass;

class CliParser
{
    private $args = null;
    private $errors = array();
    private $currentElement = null;
    /**
     * CliParser constructor.
     * Copy the content of $GLOBALS['argv'] to our own variable
     * Note that $argv is not accesible from inside a class
     */
    public function __construct(){
        $this->parse();
    }

    /**
     * Get info about the specified element/option
     * Returns an standard class with three properties:
     *      found: boolean  The option is present in the command line or not
     *      type: multi / single / missing  The value is an string, an array of values or is missing
     *      value: The value of the option or null
     * @param $element The option specified in the command line to get the value from
     * @return stdClass
     */
    public function get($element){
        $ret = new stdClass();
        if(isset($this->args[$element])){
            $value = $this->args[$element];
            if($value == ''){
                $value = null;
            }
            $ret->found = true;
            $ret->value = $value;
            if(is_array($value)){
                $ret->type = 'multi';
            }else{
                if($value == null){
                    $ret->type = 'missing';
                }else{
                    $ret->type = 'single';
                }
            }
        }else{
            $ret->found = false;
            $ret->value = null;
            $ret->type = null;
        }
        return $ret;
    }

    /**
     * Indicates if errors have been found during parsing process
     * @return bool
     */
    public function hasErrors(){
        $hasErrors = false;
        if(sizeof($this->errors) > 0){
            $hasErrors = true;
        }
        return $hasErrors;
    }

    /**
     * Returns an array with errors found during parsing
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * Main parsing code
     */
    private function parse(){
        $cnt = 0;
        $values = array();
        foreach($GLOBALS['argv'] as $arg){
            $cnt++;
            if($cnt > 1) {
                if (substr($arg, 0, 1) == '-') {
                    $this->closeElement($values);
                    $values = array();
                    $this->openElement($arg);
                } else {
                    $values[] = $arg;
                }
            }
        }
        $this->closeElement($values);
    }

    /**
     * Adds a new element/option
     * @param $element
     */
    private function openElement($element){
        if(substr($element, 0, 2) == '--'){
            $element = substr($element, 2);
        }else{
            $element = substr($element, 1);
        }
        $element = trim($element);
        if($element == ''){
            $this->errors[] = 'empty';
            $this->currentElement = 'error';
        }else{
            $this->currentElement = $element;
        }
    }

    /**
     * Assign values found associated to the current open element
     * @param $values
     */
    private function closeElement($values){
        if($this->currentElement == null){
            $this->errors[] = 'orphan object';
            $this->currenElement = 'error';
        }else{
            if(sizeof($values)==0) {
                $this->args[$this->currentElement] = '';
            }elseif(sizeof($values)==1){
                $this->args[$this->currentElement] = $values[0];
            }else{
                $this->args[$this->currentElement] = $values;
            }
            $this->currentElement = null;
        }
    }
}
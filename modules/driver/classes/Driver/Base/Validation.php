<?php
/**
 * vtemplate Driver/Base/Validation.php
 *
 * @package   Driver
 * @author    Dion Moult <dion@thinkmoult.com>
 * @copyright (c) 2013 Dion Moult
 * @license   MIT
 */

/**
 * Base driver for validation tasks
 *
 * This uses KO3's built in validation.
 *
 * @package    Driver
 */
class Driver_Base_Validation
{
    /**
     * Validation instance
     * @var Validation
     */
    private $instance;

    /**
     * Loads in the input data from the user that you want to perform validation
     * checks on.
     *
     * Example:
     * $validation = new Validation_Class;
     * $validation->setup($_POST);
     *
     * @param array $input_data
     * @return void
     */    public function setup(array $input_data)
    {
        $this->instance = Validation::factory($input_data);
    }

    /**
     * Adds a rule for checking the input with key $key using a predefined rule.
     *
     * Example:
     * $validation->rule('username', 'not_empty');
     *
     * @param string $key  The key to access the input data value
     * @param string $rule not_empty - passes if there is a value
     *                     regex - passes if it matches the regex in $arg
     *                     min_length - passes if chars are more than $arg
     *                     max_length - passes if chars are less than $arg
     *                     email - passes if it is a valid email
     *                     url - passes if it is a valid url
     *                     upload_valid - passes if upload data is valid
     *                     upload_type - passes if upload data fits filetypes in $arg array
     *                     upload_size - passes if upload data is less than size in $arg string (eg: 1M, 2KiB, 1GB)
     * @param string $arg  Any extra arguments related to the rule
     * @return void
     */
    public function rule($key, $rule, $arg = NULL)
    {
        if ($arg !== NULL)
        {
            $this->instance->rule($key, $rule, array(':value', $arg));
        }
        else
        {
            $this->instance->rule($key, $rule);
        }
    }

    /**
     * Adds a custom function to validate the input with key $key.
     *
     * Example:
     * // will call $this->is_existing_account();
     * $validation->callback($this, 'is_existing_account');
     *
     * @param string $key      The key to access the input data value
     * @param array  $function Array($object, string $function_name) which has a
     *                         return type of bool
     * @param array  $args     An array of setup keys to be used as arguments in
     *                         the callback function
     * @return void
     */
    public function callback($key, array $function, array $args)
    {
        $data = $this->instance->data();
        foreach ($args as $arg_key => $arg)
        {
            $args[$arg_key] = $data[$arg];
        }
        array_unshift($args, ':value');
        $this->instance->rule($key, $function, $args);
    }

    /**
     * Runs all of the added rules and callbacks, logging whether or not there
     * are any validation errors.
     *
     * Example:
     * if ($validation->check() === TRUE) {} // All items are valid.
     *
     * @return bool
     */
    public function check()
    {
        return $this->instance->check();
    }

    /**
     * Returns an array with any errors found.
     *
     * Example:
     * print_r($validation->check()); // return array('keyfoo', 'keybar')
     *
     * @return array
     */
    public function errors()
    {
        $errors = array();
        foreach ($this->instance->errors() as $field => $message)
        {
            $errors[] = $field;
        }
        return $errors;
    }
}

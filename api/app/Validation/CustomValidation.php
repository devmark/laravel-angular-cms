<?php namespace App\Validation;

use Illuminate\Validation\Validator;

class CustomValidation extends Validator
{

    /**
     * Magically adds validation methods. Normally the Laravel Validation methods
     * only support single values to be validated like 'numeric', 'alpha', etc.
     * Here we copy those methods to work also for arrays, so we can validate
     * if a value is OR an array contains only 'numeric', 'alpha', etc. values.
     *
     * $rules = array(
     *     'row_id' => 'required|integerInArray', // "row_id" must be an integer OR an array containing only integer values
     *     'type'   => 'inOrArray:foo,bar' // "type" must be 'foo' or 'bar' OR an array containing nothing but those values
     * );
     *
     * @param string $method Name of the validation to perform e.g. 'numeric', 'alpha', etc.
     * @param array $parameters Contains the value to be validated, as well as additional validation information e.g. min:?, max:?, etc.
     */
    public function __call($method, $parameters)
    {

        // Convert method name to its non-array counterpart (e.g. validateNumericArray converts to validateNumeric)
        $success = true;
        if (substr($method, -7) === 'InArray') {
            $method = substr($method, 0, -7);
            if (!is_array($parameters[1]))
                return false;

            foreach ($parameters[1] as $value) {
                if (is_array($value))
                    return false;
                $parameters[1] = $value;
                $success &= call_user_func_array([$this, $method], $parameters);
            }

        } else if (substr($method, -7) === 'OrArray') {
            if (!is_array($parameters[1]))
                return call_user_func_array([$this, $method], $parameters);
            // Call original method when we are dealing with a single value only, instead of an array
            foreach ($parameters[1] as $value) {
                if (is_array($value))
                    return false;
                $parameters[1] = $value;
                $success &= call_user_func_array([$this, $method], $parameters);
            }
        }

        return $success;

    }

    /**
     * All ...OrArray validation functions can use their non-array error message counterparts
     *
     * @param mixed $attribute The value under validation
     * @param string $rule Validation rule
     */
    protected function getMessage($attribute, $rule)
    {

        if (substr($rule, -7) === 'InArray')
            $rule = substr($rule, 0, -7);
        else if (substr($rule, -7) === 'OrArray')
            $rule = substr($rule, 0, -7);

        return parent::getMessage($attribute, $rule);

    }

    /**
     * Get the number of records that exist in storage.
     *
     * @param  string $table
     * @param  string $column
     * @param  mixed $value
     * @param  array $parameters
     * @return int
     */
    protected function getExistCount($table, $column, $value, $parameters)
    {
        $verifier = $this->getPresenceVerifier();

        if (substr($table, 0, 7) === 'master_') {
            $verifier->setConnection('mysql');
        }

        $extra = $this->getExtraExistConditions($parameters);

        if (is_array($value)) {
            return $verifier->getMultiCount($table, $column, $value, $extra);
        } else {
            return $verifier->getCount($table, $column, $value, null, null, $extra);
        }
    }


    /**
     * Validate the uniqueness of an attribute value on a given database table.
     *
     * If a database column is not specified, the attribute will be used.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @param  array $parameters
     * @return bool
     */
    protected function validateUnique($attribute, $value, $parameters)
    {
        $verifier = $this->getPresenceVerifier();
        $this->requireParameterCount(1, $parameters, 'unique');

        $table = $parameters[0];

        if (substr($table, 0, 7) === 'master_') {
            $verifier->setConnection('mysql');
        }

        // The second parameter position holds the name of the column that needs to
        // be verified as unique. If this parameter isn't specified we will just
        // assume that this column to be verified shares the attribute's name.
        $column = isset($parameters[1]) ? $parameters[1] : $attribute;

        list($idColumn, $id) = [null, null];

        if (isset($parameters[2])) {
            list($idColumn, $id) = $this->getUniqueIds($parameters);

            if (strtolower($id) == 'null') $id = null;
        }

        // The presence verifier is responsible for counting rows within this store
        // mechanism which might be a relational database or any other permanent
        // data store like Redis, etc. We will use it to determine uniqueness.
        $verifier = $this->getPresenceVerifier();

        $extra = $this->getUniqueExtra($parameters);

        return $verifier->getCount(

            $table, $column, $value, $id, $idColumn, $extra

        ) == 0;
    }

    public function validatePhone($attribute, $value, $parameters)
    {
        return preg_match("/^([0-9\s\-\+\(\)]*)$/", $value);
    }

}
<?php
namespace ProcessMaker\Services\Api;

class Type
{
    /**
     * Email validation
     *
     * @param string $email {@from body}{@type email}
     */
    public function postEmail($email)
    {
        return $email;
    }

    /**
     * Date validation
     *
     * @param string $date {@from body}{@type date}
     */
    public function postDate($date)
    {
        return $date;
    }

    /**
     * DateTime validation
     *
     * @param string $datetime {@from body}{@type datetime}
     */
    public function postDatetime($datetime)
    {
        return $datetime;
    }

    /**
     * time validation
     *
     * @param string $time {@from body}{@type time}
     */
    public function postTime($time)
    {
        return $time;
    }

    /**
     * time validation in 12 hour format
     *
     * @param string $time {@from body}{@type time12}
     */
    public function postTime12($time12)
    {
        return $time12;
    }

    /**
     * Timestamp validation
     *
     * @param string $timestamp {@from body}{@type timestamp}
     */
    public function postTimestamp($timestamp)
    {
        return $timestamp;
    }

    /**
     * Integer validation
     *
     * @param array $integers {@type int}
     */
    public function postIntegers(array $integers)
    {
        return $integers;
    }

    /**
     * Array of numbers
     *
     * @param array $numbers {@type float}
     */
    public function postNumbers(array $numbers)
    {
        return $numbers;
    }

    /**
     * Array of time strings
     *
     * @param array $timestamp {@from body}{@type time}
     */
    public function postTimes(array $timestamps)
    {
        return $timestamps;
    }

    /**
     * Array of timestamps
     *
     * @param array $timestamp {@from body}{@type timestamp}
     */
    public function postTimestamps(array $timestamps)
    {
        return $timestamps;
    }

    /**
     * Custom class parameter
     *
     * @param array $definition
     * @param StructProperties $properties
     *
     * @return Author
     */
    public function postAuthor(Author $author)
    {
        return $author;
    }

    /**
     * Array of authors
     *
     * @param array $authors {@type Author}
     *
     * @return mixed
     */
    public function postAuthors(array $authors)
    {
        return $authors;
    }

    /**
     * An associative array
     *
     * @param array $object {@type associative}
     *
     * @return array
     */
    public function postObject(array $object)
    {
        return $object;
    }

    /**
     * An indexed array
     *
     * @param array $array {@type indexed}
     *
     * @return array
     */
    public function postArray(array $array)
    {
        return $array;
    }

    /**
     * An array indexed or associative
     *
     * @param array $array
     *
     * @return array
     */
    public function postArrayOrObject(array $array)
    {
        return $array;
    }

    /**
     * @param string $gender {@from body}{@choice male,female}
     */
    public function postChoise($gender)
    {
        return $gender;
    }

    /**
     * @param string $name $name 3 to 10 characters in length {@from body}{@min 3}{@max 10}
     */
    public function postMinmax($name)
    {
        return $name;
    }

    /**
     * @param string $name $name 3 to 10 characters in length {@from body}{@min 3}{@max 10}{@fix true}
     */
    public function postMinmaxfix($name)
    {
        return $name;
    }

    /**
     * @param integer $age {@choise 1,2}
     */
    public function postInt($age = '')
    {
        return $age;
    }
}

class Author
{
    /**
     * @var string {@from body} {@min 3}{@max 100}
     * name of the Author {@required true}
     */
    public $name = 'Name';
    /**
     * @var string {@type email} {@from body}
     * email id of the Author
     */
    public $email = 'name@domain.com';
}


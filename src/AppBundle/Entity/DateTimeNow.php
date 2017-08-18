<?php

namespace AppBundle\Entity;

class DateTimeNow
{
    public function format()
    {
        return date("Y-m-d H:i:s");
    }
}
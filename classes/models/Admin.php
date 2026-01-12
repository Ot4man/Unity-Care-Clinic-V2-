<?php


class Admin extends User
{
    public function getRole()
    {
        return "admin";
    }
}

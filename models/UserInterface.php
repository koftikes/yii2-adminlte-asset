<?php

namespace sbs\models;

/**
 * Interface UserInterface
 * @package sbs\models
 */
interface UserInterface
{
    /**
     * Should return FirstName and LastName
     * @return string
     */
    public function getName();

    /**
     * Should return path to avatar. See Twig User Extension.
     * @return string
     */
    public function getAvatar();

    /**
     * Should return Date of Registration
     * @return mixed
     */
    public function getMemberSince();

    /**
     * Should return Description (can be role, group or etc.)
     * @return string
     */
    public function getTitle();

    /**
     * Can return Additional Info
     * @return mixed
     */
    public function getInfo();
}

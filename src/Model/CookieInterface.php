<?php


namespace Linktracker\Tracking\Model;


interface CookieInterface
{

    /**
     * Get cookie name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get cookie duration
     *
     * @return int
     */
    public function getDuration(): int;

    /**
     * Set value
     *
     * @param string $value
     */
    public function setValue(string $value): void;

    /**
     * Get value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Tell if cookie exists
     *
     * @return bool
     */
    public function exists(): bool;

}

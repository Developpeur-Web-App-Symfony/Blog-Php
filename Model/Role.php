<?php
namespace Model;

class Role extends \Framework\Model
{
    private mixed $id;
    private mixed $role;
    private mixed $slug;
    private mixed $level;

    /**
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRole(): mixed
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole(mixed $role): void
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getSlug(): mixed
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug(mixed $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getLevel(): mixed
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel(mixed $level): void
    {
        $this->level = $level;
    }


}
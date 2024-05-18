<?php

namespace App\Entity;

/**
 * This class contains all the data needed for a contribution.
 *
 */
class Contribution
{
    // Wikipedia link of contribution
    public string $link;
    // IP of contributor
    public string $ip;
    // IP range used
    public string $iprange;
    // Date of contribution. Int because unix timestamp bla bla bla
    public int $date;
    // Title of contribution
    public string $title;
    // Comment on contribution
    public string $comment;
    // Size of contribution
    public string $size;
    // Wiki used
    public string $wiki;
    // State body of contribution
    public string $body;
    // Revision ID
    public int $revid;

    public function setLink(string $link) {
        $this->link = $link;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIprange(string $iprange)
    {
        $this->iprange = $iprange;
    }

    public function getIprange(): string
    {
        return $this->iprange;
    }

    public function setDate(int $date)
    {
        $this->date = $date;
    }

    public function getDate(): int
    {
        return $this->date;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setSize(string $size)
    {
        $this->size = $size;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setWiki(string $wiki)
    {
        $this->wiki = $wiki;
    }

    public function getWiki(): string
    {
        return $this->wiki;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setRevid(string $revid)
    {
        $this->revid = $revid;
    }
}
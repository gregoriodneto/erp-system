<?php

namespace App\Sessions;

class SessionManager
{
    public static function init()
    {
        if (!isset($_SESSION)) session_start();
        if (!isset($_SESSION["cart"])) $_SESSION["cart"] = [];
    }
    public static function add($data)
    {
        self::init();

        foreach ($_SESSION["cart"] as &$item) 
        {
            if ($item["variation_id"] === $data["variation_id"])
            {
                $item["quantity"] += $data["quantity"];
                return;
            }
        }

        return $_SESSION["cart"][] = $data;
    }

    public static function remove($variation_id)
    {
        self::init();
        $_SESSION["cart"] = array_filter($_SESSION["cart"], function ($item) use ($variation_id) {
            return $item["variation_id"] !== (int)$variation_id;
        });
    }

    public static function view()
    {
        self::init();
        return $_SESSION["cart"];
    }

    public static function total()
    {
        self::init();

        $subtotal = 0;

        foreach ($_SESSION["cart"] as &$item)
        {
            $subtotal += $item["price"] * $item["quantity"];
        }
        
        return $subtotal;
    }

    public static function clear()
    {
        self::init();
        $_SESSION["cart"] = [];
    }
}
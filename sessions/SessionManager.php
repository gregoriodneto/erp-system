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

        foreach ($_SESSION["cart"]["items"] as &$item) 
        {
            if ($item["variation_id"] === $data["variation_id"])
            {
                $item["quantity"] += $data["quantity"];
                return;
            }
        }

        return $_SESSION["cart"]["items"][] = $data;
    }

    public static function remove($variation_id)
    {
        self::init();
        $_SESSION["cart"]["items"] = array_filter($_SESSION["cart"]["items"], function ($item) use ($variation_id) {
            return $item["variation_id"] !== (int)$variation_id;
        });
    }

    public static function removeCoupon()
    {
        self::init();
        $_SESSION["cart"]["coupon"] = [];
    }

    public static function addCoupon($coupon)
    {
        self::init();
        return $_SESSION["cart"]["coupon"] = [
            "id"               => $coupon["id"],
            "code"             => $coupon["code"],
            "type_discount"    => $coupon["type_discount"],
            "discount_value"   => (int)$coupon["discount_value"],
            "validity"         => $coupon["validity"],
            "minimum_subtotal" => (int)$coupon["minimum_subtotal"],
        ];
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

        foreach ($_SESSION["cart"]["items"] as &$item)
        {
            $subtotal += $item["price"] * $item["quantity"];
        }

        $discount = 0;
        if (!empty($_SESSION["cart"]["coupon"]))
        {
            $coupon = $_SESSION["cart"]["coupon"];

            if (strtotime($coupon["validity"]) >= time() &&
                $subtotal >= $coupon["minimum_subtotal"]
            ) {
                if ($coupon["type_discount"] === "percent")
                {
                    $discount = ($coupon["discount_value"] / 100) * $subtotal;
                }
                else if ($coupon["type_discount"] === "fixed")
                {
                    $discount = $coupon["discount_value"];
                }

            }
        }

        $subtotal_with_discount = max(0 , $subtotal - $discount);
        
        $frete = $subtotal === 0 || $subtotal > 200 ? 0 :
                ($subtotal >= 52 && $subtotal <= 166.59 ? 15 : 20);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'freight' => $frete,
            'total' => $subtotal_with_discount + $frete
        ];
    }

    public static function clear()
    {
        self::init();
        $_SESSION["cart"] = [
            "items" => [],
            "coupon" => []
        ];
    }
}
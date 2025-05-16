<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Helpers\Response;
use App\Core\Helpers\Validator;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Variation;
use App\Sessions\SessionManager;

class CartController extends Controller
{
    private Variation $variation;
    private Product $product;
    private Coupon $coupon;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->variation = new Variation($pdo);
        $this->product = new Product($pdo);
        $this->coupon = new Coupon($pdo);
    }

    public function index()
    {
        try {
            $cart = SessionManager::view();
            Response::success("Listagem do Carrinho de Compras", $cart);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $missing = Validator::requiredFields(
                ['variation_id', 'quantity'],
                $data
            );
            if (!empty($missing))
            {
                Response::error(
                    'Campos obrigatórios ausentes: ' . implode(',', $missing),
                    422
                );
            }

            $variation_product = $this->variation->findById($data['variation_id']);
            if (empty($variation_product))
            {
                Response::error('Nenhuma variação do produto encontrado.',500);
            }

            $product = $this->product->findById($variation_product['product_id']);
            if (empty($product))
            {
                Response::error('Nenhuma produto encontrado nesta variação.',500);
            }

            $cart = SessionManager::add([
                'variation_id' => $variation_product['id'],
                'price'=> $product['price_base'] + $variation_product['additional_price'],
                'name' => $variation_product['name'],
                'quantity' => $data['quantity']
            ]);

            Response::success('Item inserido no carrinho com sucesso!', $cart);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }

    public function storeCoupon()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            $missing = Validator::requiredFields(
                ['coupon_id'],
                $data
            );
            if (!empty($missing))
            {
                Response::error(
                    'Campos obrigatórios ausentes: ' . implode(',', $missing),
                    422
                );
            }

            $coupon = $this->coupon->findById($data['coupon_id']);
            if (empty($coupon))
            {
                Response::error('Nenhum cupom encontrado.',500);
            }

            $coupon_cart = SessionManager::addCoupon([
                "id"               => $coupon["id"],
                "code"             => $coupon["code"],
                "type_discount"    => $coupon["type_discount"],
                "discount_value"   => $coupon["discount_value"],
                "validity"         => $coupon["validity"],
                "minimum_subtotal" => $coupon["minimum_subtotal"],            
            ]);

            Response::success('Cupom inserido no carrinho com sucesso!', $coupon_cart);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }

    public function remove($variation_id)
    {
        try {
            if (!$variation_id)
            {
                Response::error(
                    'Campos obrigatórios ausentes: variation_id',
                    422
                );
            }
            SessionManager::remove($variation_id);
            Response::success("Item removido do carrinho com sucesso!", []);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }

    public function removeCoupon()
    {
        try {            
            SessionManager::removeCoupon();
            Response::success("Cupon removido com sucesso!", []);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }

    public function total()
    {
        try {
            $data = SessionManager::total();
            Response::success("Valor total do carrinho.", $data);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }

    public function clear()
    {
        try {
            SessionManager::clear();
            Response::success("Sem nenhum item no carrinho de compras.", null);
        } catch (\Throwable $th) {
            Response::error($th->getMessage(), 500);
        }
    }
}
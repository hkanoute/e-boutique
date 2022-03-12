<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartLine;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index_market')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $product = $this->em->getRepository(Product::class)->findAll();

        if ($user){
            $session->set("email", $user->getEmail());
            $session->set("name", $user->getName());
            $session->set("name", $user->getName());
            $address = count($user->getAddress());
           if ($address === 0){
               return $this->redirectToRoute("customer_address_new");
           }
        }
        $cart = $this->em->getRepository(Cart::class)->findOneByActive(1);
        if ($cart !== null) {
            $cartline = count($this->em->getRepository(CartLine::class)->findByCartId($cart->getId()));
        }else{
            $cartline = 0;
        }
        return $this->render('index/index.html.twig', [
            'cartline' => $cartline,
            'products' => $product
        ]);
    }

    #[Route('/products', name: 'get_products')]
    public function getProducts(){
        $products = $this->em->getRepository(Product::class)->findAll();
        return $this->json($products,200,[],["groups"=> "product"]);
    }

    #[Route('/cart', name: 'add_carte')]
    public function add_cart(Request $request): Response
    {
        $productReseved = json_decode($request->request->get('product'));
        $quantity = json_decode($request->request->get('quantity'));
        $product = $this->em->getRepository(Product::class)->findOneByName($productReseved[0]->name);
        $cart = $this->em->getRepository(Cart::class)->findOneByActive(1);
        $cart = $cart === null ? $this->newCart() : $this->em->getRepository(Cart::class)->findOneByActive(1);
        $this->em->persist($cart);
        $this->em->flush();
        $cartLine = new CartLine();
        $cartLine->setCart($cart);
        $cartLine->setProduct($product);
        $cartLine->setQuantity($quantity);
        $this->em->persist($cartLine);
        $this->em->flush();

        return new Response("ok");
    }

    #[Route('/cartValidate', name: 'valid_carte')]
    public function valid_cart(Request $request): Response
    {
        $cart = $this->em->getRepository(Cart::class)->findOneByActive(1);
        if ($cart !== null) {
            $cart->setIsActive(0);
            $this->em->persist($cart);
            $this->em->flush();
        }
        $productRepository = $this->em->getRepository(Product::class);
        $products = $productRepository->findAll();

        $cartline = $request->request->get('cartLine');

        return $this->redirectToRoute("index_market",
            ['products' => $products,
            'cartline' => $cartline
            ]);
    }


    #[Route('/cart_products', name: 'cart_products')]
    public function cart_products(Request $request): Response
    {
        $cart = $this->em->getRepository(Cart::class)->findOneByActive(1);
        $products = $cart !== null ? $this->em->getRepository(CartLine::class)->findByCartId($cart->getId()) : "";
        $cartline = $request->request->get('cartNumber');



        return $this->render("cart/cart.html.twig",["product" => $products, 'cartline' => $cartline]);
        //return $this->json($products,200,[],["groups"=> "product"]);
    }


    private function newCart()
    {
        $cart = new Cart();
        $cart->setIsActive(1);
        return $cart;
    }

}

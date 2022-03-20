<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\Type\AddressType;
use App\Repositories\Doctrine\AddressRepository;
use App\Repositories\Doctrine\CityRepository;
use App\Services\AddressService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class AddressController extends AbstractController
{
    public function index(Request $request, AddressRepository $addressRepository): Response
    {
        $first = $request->query->get('first',0);
        $rows = $request->query->get('first',2);

        $addressService = new AddressService($addressRepository);
        $addressList = $addressService->getAll($first,$rows);
        return $this->render('address/index.html.twig', [
            'addressList' => $addressList,
        ]);
    }
    public function store(Request $request, AddressRepository $addressRepository, CityRepository $cityRepository, SluggerInterface $slugger): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $addressService = new AddressService($addressRepository, $cityRepository);
            $address = $form->getData();
            $addressService->create($address, $form, $slugger);
            $this->addFlash('success', 'Address Has been successfully registered!');
            return $this->redirect('/');
        } else {
            $this->addFlash('error', 'Failure during register address!');
        }

        return $this->render('address/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\Type\AddressDeleteType;
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
        $rows = $request->query->get('rows',7);

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
            $addressData = $form->getData();
            $addressService->create($addressData, $form, $slugger);
            $this->addFlash('success', 'Address Has been successfully registered!');
            return $this->redirect('/');
        } else {
            $this->addFlash('error', 'Failure during register address!');
        }

        return $this->render('address/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function update(Request $request, AddressRepository $addressRepository, CityRepository $cityRepository, SluggerInterface $slugger): Response
    {
        $addressService = new AddressService($addressRepository, $cityRepository);
        $id = $request->attributes->get('id',0);
        $address = $addressService->find($id);
        $form = $this->createForm(AddressType::class, $address);
        if($address !== null) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $addressData = $form->getData();
                $addressService->update($address->getId(),$addressData, $form, $slugger);
                $this->addFlash('success', 'Address Has been successfully updated!');
                return $this->redirect('/');
            } else {
                $this->addFlash('error', 'Failure during update address!');
            }
        } else {
            $this->addFlash('error', 'Address has not been found!');
        }

        return $this->render('address/update.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }
    public function delete(Request $request, AddressRepository $addressRepository): Response
    {
        $addressService = new AddressService($addressRepository);
        $id = $request->attributes->get('id',0);
        $address = $addressService->find($id);
        $form = $this->createForm(AddressDeleteType::class, $address);
        if($address !== null) {
            if(!$form->isSubmitted()){
                $form->get('id')->setData($address->getId());
            }
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $addressService->delete($address->getId(),$form);
                $this->addFlash('success', 'Address Has been successfully deleted!');
                return $this->redirect('/');
            } else {
                $this->addFlash('error', 'Failure during delete address!');
            }
        } else {
            $this->addFlash('error', 'Address has not been found!');
        }

        return $this->render('address/delete.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }
}

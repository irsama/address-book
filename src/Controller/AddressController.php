<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\Type\AddressDeleteType;
use App\Form\Type\AddressType;
use App\Repositories\Interfaces\IAddressRepository;
use App\Repositories\Interfaces\ICityRepository;
use App\Services\AddressService;
use App\Tools\UrlParameterValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;;

class AddressController extends AbstractController
{
    public function index(Request $request, IAddressRepository $addressRepository): Response
    {
        $first = $request->attributes->getInt('first');
        $rows = $request->attributes->getInt('rows' , 7);
        $addressService = new AddressService($addressRepository);
        $addressList = $addressService->getAll($first, $rows);
        $count = $addressService->count();
        return $this->render('address/index.html.twig', [
            'addressList' => $addressList,
            'count' => $count,
            'first' => $first,
        ]);
    }
    public function store(Request $request, IAddressRepository $addressRepository, ICityRepository $cityRepository, SluggerInterface $slugger): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $emailAddress = $form->get('emailAddress')->getData();
            $addressService = new AddressService($addressRepository, $cityRepository);
            if($addressService->findByEmailAddress($emailAddress) == null) {
                $addressData = $form->getData();
                $addressService->create($addressData, $form, $slugger);
                $this->addFlash('success', 'Address Has been successfully registered!');
                return $this->redirect('/');
            } else {
                $this->addFlash('error', 'Email Address already exist!');
            }
        } elseif ($form->isSubmitted()) {
            $this->addFlash('error', 'Failure during register address!');
        }

        return $this->render('address/store.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function update(Request $request, IAddressRepository $addressRepository, ICityRepository $cityRepository, SluggerInterface $slugger): Response
    {
        $addressService = new AddressService($addressRepository, $cityRepository);
        $address = $this->getAddress($request, $addressService);
        $form = $this->createForm(AddressType::class, $address);
        if($address) {
            if(!$form->isSubmitted()){
                $form->get('id')->setData($address->getId());
            }
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $emailAddress = $form->get('emailAddress')->getData();
                $addressService = new AddressService($addressRepository, $cityRepository);
                if($addressService->findByEmailAddress($emailAddress, $address->getId()) == null) {
                    $addressData = $form->getData();
                    $addressService->update($address->getId(), $addressData, $form, $slugger);
                    $this->addFlash('success', 'Address Has been successfully updated!');
                    return $this->redirect('/');
                } else {
                    $this->addFlash('error', 'Email Address already exist!');
                }
            } elseif ($form->isSubmitted()) {
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
    public function delete(Request $request, IAddressRepository $addressRepository): Response
    {
        $addressService = new AddressService($addressRepository);
        $address = $this->getAddress($request, $addressService);
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
            } elseif ($form->isSubmitted()) {
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

    /**
     * @param Request $request
     * @param AddressService $addressService
     * @return Address|null
     */
    public function getAddress(Request $request, AddressService $addressService): ?Address
    {
        $id = $request->attributes->getInt('id');
        return $addressService->find($id);
    }
}

<?php

namespace App\Services;

use App\Entity\Address;
use App\Repositories\Interfaces\IAddressRepository;
use App\Repositories\Interfaces\ICityRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class AddressService
{
    private $addressRepository;
    private $cityRepository;
    public function __construct(IAddressRepository $addressRepository, ICityRepository $cityRepository = null){
        $this->addressRepository = $addressRepository;
        $this->cityRepository = $cityRepository;
    }
    public function getAll($first=-1, $rows=7): array
    {
        return $this->addressRepository->getAll($first, $rows);
    }
    public function find($id): ?Address
    {
        return $this->addressRepository->find($id);
    }
    public function delete($id, $form = null)
    {
        if($form !== null){
            $formId = $form->get('id')->getData();
            if($id != $formId){
                return;
            }
        }
        $this->addressRepository->delete($id);
    }
    public function create(Address $address, $form = null, SluggerInterface $slugger = null): Address
    {
        if($form !== null) {
            $city = $this->cityRepository->find($form->get('chosenCity')->getData());
            $address->setCity($city);
            $pictureFile = $form->get('pictureFile')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        'uploadedFile/address/images',
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $address->setPicture($newFilename);
            }
        }

        return $this->addressRepository->create($address);
    }
    public function update(int $id, Address $address, $form = null, SluggerInterface $slugger = null): Address
    {
        if($form !== null) {
            $currentAddress = $this->addressRepository->find($id);
            $city = $this->cityRepository->find($form->get('chosenCity')->getData());
            $address->setCity($city);
            $pictureFile = $form->get('pictureFile')->getData();
            if ($pictureFile) {
                $currentPicture = $currentAddress->getPicture();
                if($currentPicture!==null){
                    $filesystem = new Filesystem();
                    $path = realpath($_SERVER["DOCUMENT_ROOT"]).'/uploadedFile/address/images/'.$currentPicture;
                    if($filesystem->exists($path)){
                        $filesystem->remove($path);
                    }
                }
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        'uploadedFile/address/images',
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $address->setPicture($newFilename);
            }
        }

        return $this->addressRepository->update($id, $address);
    }
}
<?php

namespace App\Controller;

use App\Repositories\Interfaces\ICountryRepository;
use App\Services\CountryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends AbstractController
{
    public function getCities(Request $request, ICountryRepository $countryRepository): Response
    {
        $countryId = $request->attributes->getInt('id');
        $countryService = new CountryService($countryRepository);
        $cities = $countryService->getCities($countryId);
        return $this->json($cities);
    }
}

<?php

namespace App\Controller;

use App\Repositories\Doctrine\CountryRepository;
use App\Services\CountryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends AbstractController
{
    public function getCities(Request $request, CountryRepository $countryRepository): Response
    {
        $countryId = $request->query->has('id') ? $request->query->get('id'):$request->attributes->get('id');
        $countryService = new CountryService($countryRepository);
        $cities = $countryService->getCities($countryId);
        return $this->json($cities);
    }
}

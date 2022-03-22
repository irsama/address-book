<?php

namespace App\Controller;

use App\Repositories\Interfaces\ICountryRepository;
use App\Services\CountryService;
use App\Tools\UrlParameterValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;

class CountryController extends AbstractController
{
    public function getCities(Request $request, ICountryRepository $countryRepository): Response
    {
        $constraints = new Collection([
            'id' => [new Optional(new Type(['type' => 'integer']))],
        ]);
        $countryId = $request->query->has('id') ? $request->query->get('id'):
            UrlParameterValidator::validate($request->query->all(), $constraints) === true ?
            $request->attributes->get('id',0):0;
        $countryService = new CountryService($countryRepository);
        $cities = $countryService->getCities($countryId);
        return $this->json($cities);
    }
}

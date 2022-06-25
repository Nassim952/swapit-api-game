<?php
namespace App\lib\IgdbBundle\Mapper;

use App\Entity\Company;
use Symfony\Component\Serializer;

class CompanyMapper
{
    public static function map(array $input, \Doctrine\ORM\EntityManager $em): ?Company
    {
        if ($em->getRepository(Company::class)->find($input['id'])) {

            return null;
        }
        
        // $company =  $serializer->deserialize($data, Company::class, 'json');

        $company = new Company();
        $company->setId($input['id']);
        $company->setName($input['name']);
        $company->setCountry($input['country'] ?? null);
        $company->setDescription($input['description'] ?? null);

        return $company;
    }
}
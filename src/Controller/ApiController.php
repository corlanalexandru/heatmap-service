<?php


namespace App\Controller;


use App\Factory\HistoryFactory;
use App\Helper\ApiResponses;
use App\Helper\JsonProvider;
use App\Preparator\HistoryPreparator;
use App\Repository\CustomersRepository;
use App\Repository\HistoryRepository;
use App\Repository\TypesRepository;
use App\Validator\HistoryValidator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/")
 */
class ApiController extends AbstractController
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("visit", name="api-visit", methods={"POST"})
     * @param CustomersRepository $customersRepository
     * @param HistoryFactory $historyFactory
     * @param HistoryRepository $historyRepository
     * @param HistoryPreparator $historyPreparator
     * @param HistoryValidator $historyValidator
     * @param Request $request
     * @return JsonResponse
     */
    public function addVisit
    (
        CustomersRepository $customersRepository,
        HistoryFactory $historyFactory,
        HistoryRepository $historyRepository,
        HistoryPreparator $historyPreparator,
        HistoryValidator $historyValidator,
        Request $request
    ): JsonResponse
    {
        $data = $historyPreparator->prepare(json_decode($request->getContent(), true));
        $validator = $historyValidator->validate($data);
        if(count($validator) > 0) {
            return new JsonResponse(['message'=>ApiResponses::VALIDATION_FAILED['MESSAGE'], 'errors' => $validator],ApiResponses::VALIDATION_FAILED['CODE']);
        }

        try {
            $customersRepository->save($data['customer']);
        }
        catch (\Exception $e) {
            $this->logger->critical('Database insert failed'. $e->getMessage());
        }

        $history = $historyFactory->create($data['url'], $data['fullUrl'], $data['type'], $data['customer'], $data['parameters']);

        try {
            $historyRepository->save($history);
        }
        catch (\Exception $e) {
            $this->logger->critical('Database insert failed'. $e->getMessage());
        }
        return new JsonResponse(['message'=>ApiResponses::RESOURCE_CREATED['MESSAGE']],ApiResponses::RESOURCE_CREATED['CODE']);
    }

    /**
     * @Route("customer/{uid}/journey", name="api-customer-journey", methods={"GET"})
     * @param string $uid
     * @param CustomersRepository $customersRepository
     * @param HistoryRepository $historyRepository
     * @param JsonProvider $jsonProvider
     * @param Request $request
     * @return JsonResponse
     */
    public function listCustomerJourney
    (
        string $uid,
        CustomersRepository $customersRepository,
        HistoryRepository $historyRepository,
        JsonProvider $jsonProvider,
        Request $request
    ): JsonResponse
    {
        $customer = $customersRepository->findOneBy(['uid' => $uid]);
        $from = $request->get('from','');
        $until = $request->get('until','');
        $limit = ((int)$request->get('limit')) ?: $this->getParameter('API_LISTING_DEFAULT_LIMIT');
        if($customer === null) {
            return new JsonResponse(['message'=>ApiResponses::RESOURCE_NOT_FOUND['MESSAGE']],ApiResponses::RESOURCE_NOT_FOUND['CODE']);
        }
        $history = $historyRepository->findHistoryByCustomer($customer, $from, $until, $limit);
        $context = ['attributes' => ['url', 'fullUrl', 'parameters', 'createdAt', 'type' => ['id','name'] ]];
        $result = $jsonProvider->provideJsonFromContext($history, $context, ['createdAt']);
        return new JsonResponse($result);
    }

    /**
     * @Route("types/hits", name="api-types-hits", methods={"GET"})
     * @param TypesRepository $typesRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function listTypeHits
    (
        TypesRepository $typesRepository,
        Request $request
    ): JsonResponse
    {
        $from = $request->get('from','');
        $until = $request->get('until','');
        return new JsonResponse($typesRepository->findTypeHits($from, $until));
    }


    /**
     * @Route("links/hits", name="api-links-hits", methods={"GET"})
     * @param HistoryRepository $historyRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function listLinksHits
    (
        HistoryRepository $historyRepository,
        Request $request
    ): JsonResponse
    {
        $from = $request->get('from','');
        $until = $request->get('until','');
        $exact = $request->get('exact',true);
        return new JsonResponse($historyRepository->findLinkHits($from, $until, $exact));
    }

    /**
     * @Route("customers-journey/similar/{uid}", name="api-customers-similar-journey", methods={"GET"})
     * @param string $uid
     * @param CustomersRepository $customersRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function listCustomersWithSimilarJourney
    (
        string $uid,
        CustomersRepository $customersRepository,
        Request $request
    ): JsonResponse
    {
        $customer = $customersRepository->findOneBy(['uid' => $uid]);
        $limit = ((int)$request->get('limit')) ?: $this->getParameter('API_LISTING_SIMILAR_JOURNEY_USERS');

        if($customer === null) {
            return new JsonResponse(['message'=>ApiResponses::RESOURCE_NOT_FOUND['MESSAGE']],ApiResponses::RESOURCE_NOT_FOUND['CODE']);
        }

        return new JsonResponse($customersRepository->findCustomersWithSimilarJourney($customer,$limit));
    }
}